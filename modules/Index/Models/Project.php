<?php

namespace webu\modules\Index\Models;

use webu\Database\StructureTables\WebuProjects;
use webu\modules\Index\Database\ProjectsTable;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Query\Types\QuerySelect;

class Project {

    const PREVIEW_LENGTH = 200;

    /** @var string  */
    private $title = "";
    /** @var string  */
    private $createdAt = "";
    /** @var string  */
    private $updatedAt = "";
    /** @var string  */
    private $content = "";
    /** @var integer  */
    private $id = -1;
    /** @var array  */
    private $language = array();



    public function __construct($title="", $content="", $language=[], $id=-1, $createdAt="", $updatedAt="")
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setLanguage($language);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }


    public function save(DatabaseConnection $connection) {
        if($this->findById($this->id, $connection, true)) {
            //already exists -> update

            $qb = new QueryBuilder($connection);
            $qb->update(WebuProjects::TABLENAME)
                ->set(WebuProjects::COL_TITLE, $this->getTitle(), ':title')
                ->set(WebuProjects::COL_CONTENT, $this->getContent(), ':content')
                ->set(WebuProjects::COL_LANGUAGES, $this->getLanguageJson(), ':languages')
                ->execute();
        }
        else {
            //doesnt exist yet -> create

            $qb = new QueryBuilder($connection);
            $qb->insert()
                ->into(WebuProjects::TABLENAME)
                ->setValue(WebuProjects::COL_TITLE, $this->getTitle())
                ->setValue(WebuProjects::COL_CONTENT, $this->getContent())
                ->setValue(WebuProjects::COL_LANGUAGES, $this->getLanguageJson())
                ->execute();
        }
    }


    public static function findById(int $id, DatabaseConnection $connection, $asBool = false) {
        if($id < 0) return false;

        $qb = new QueryBuilder($connection);
        $erg = $qb->select("*")
            ->from(WebuProjects::TABLENAME)
            ->where(WebuProjects::COL_ID, $id, false, false, ':id')
            ->limit(1)
            ->execute();

        if($asBool) {
            return (!!$erg);
        }
        else {
            if(!!$erg==false || sizeof($erg) < 1) return false;
            else return new static(
                $erg[0][WebuProjects::RAW_COL_TITLE],
                $erg[0][WebuProjects::RAW_COL_CONTENT],
                $erg[0][WebuProjects::RAW_COL_LANGUAGES],
                $erg[0][WebuProjects::RAW_COL_ID],
                $erg[0][WebuProjects::RAW_COL_CREATED_AT],
                $erg[0][WebuProjects::RAW_COL_UPDATED_AT]
            );
        }
    }


    public static function find(int $offset, int $length, DatabaseConnection $connection) {
        if($length == 0) return [];

        $qb = new QueryBuilder($connection);
        $erg = $qb->select("*")
            ->from(WebuProjects::TABLENAME)
            ->limit($offset, $length)
            ->execute();

        if(!!$erg==false || sizeof($erg) < 1) return [];

        $entities = array();
        foreach($erg as $entity) {
            $entities[] = new static(
                $entity[WebuProjects::RAW_COL_TITLE],
                $entity[WebuProjects::RAW_COL_CONTENT],
                $entity[WebuProjects::RAW_COL_LANGUAGES],
                $entity[WebuProjects::RAW_COL_ID],
                $entity[WebuProjects::RAW_COL_CREATED_AT],
                $entity[WebuProjects::RAW_COL_UPDATED_AT]
            );
        }
        return $entities;
    }


    public static function getCount(DatabaseConnection $connection)
    {
        $qb = new QueryBuilder($connection);
        $erg = $qb->select(QuerySelect::COUNT)
            ->from(WebuProjects::TABLENAME)
            ->execute();

        if (!!$erg == false || sizeof($erg) < 1) return 0;

        return $erg[0]["count"];
    }




        /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getLanguage(): array
    {
        return $this->language;
    }


    /**
     * @return string
     */
    public function getLanguageJson(): string
    {
        return json_encode($this->language);
    }

    /**
     * @param string|array $languageIds
     */
    public function setLanguage($languageIds): void
    {
        if(is_array($languageIds))          $this->language = $languageIds;
        else if(is_string($languageIds))    $this->setLanguageJson($languageIds);
    }


    /**
     * @param string $languageJson
     */
    public function setLanguageJson(string $languageJson): void
    {
        $this->language = (array)json_decode($languageJson);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }








}