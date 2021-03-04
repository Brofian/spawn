<?php

namespace webu\modules\Index\Controller;

use MongoDB\Driver\Query;
use webu\Database\StructureTables\WebuContactMessages;
use webu\modules\Index\Database\ContactTable;
use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\core\Request;
use webu\system\core\Response;


class Api extends BaseController
{


    public function onControllerStart(Request $request, Response $response)
    {
        //this is just an api, so return true by default
        $response->getTwigHelper()->setOutput(json_encode(true));
    }

    /*
     *
     * Callable Actions
     *
     */


    public function contactFormSubmit(Request $request, Response $response) {

        $post = $request->getParamPost();

        if(!isset($post["email"],$post["subject"],$post["message"])) {
            $response->getTwigHelper()->setOutput(json_encode([
                "success"=>"false",
                "problem"=>"missing_value"
            ]));
            return;
        }

        if($post["url"] && $post["url"] != "") {
            $response->getTwigHelper()->setOutput(json_encode([
                "success"=>"false",
                "problem"=>"bot_detection"
            ]));
            return;
        }

        $email_pattern = '/^([^@]*)@([^@\.]*)\.([^@.]*)$/m';

        if(!preg_match($email_pattern, $post["email"])) {
            $response->getTwigHelper()->setOutput(json_encode([
                "success"=>"false",
                "problem"=>"invalid_email"
            ]));
            return;
        }


        //everything seems to be fine -> create db entry
        $queryBuilder = new QueryBuilder($request->getDatabaseHelper()->getConnection());
        $query = $queryBuilder->insert();
        $query->into(WebuContactMessages::TABLENAME)
            ->setValue(WebuContactMessages::COL_EMAIL, $post["email"], ":email")
            ->setValue(WebuContactMessages::COL_SUBJECT, $post["subject"], ":subject")
            ->setValue(WebuContactMessages::COL_MESSAGE, $post["message"], ":message");
        $query->execute();


        $response->getTwigHelper()->setOutput(json_encode([
            "success"=>"true",
        ]));
    }

}