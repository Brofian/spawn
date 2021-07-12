<?php declare(strict_types=1);

namespace src\npm;

use spawn\system\Core\Base\Custom\FileEditor;

class WebpackConfigGenerator {

    const REL_PATH = "/webpack.config.js";
    const REL_LAYOUT_PATH = self::REL_PATH . ".layout";



    public static function rewriteConf($current_namespace, $namespace_list) {

        //read layout
        $configFileContent = FileEditor::getFileContent(__DIR__ . self::REL_LAYOUT_PATH);


        //update current namespace
        $configFileContent = str_replace("{{namespace_current}}", $current_namespace, $configFileContent);

        //update namespace list for imports
        $moduleList = "";
        foreach($namespace_list as $namespace) {
            $moduleList .= "path.resolve(__dirname, '../../var/cache/private/resources/".$namespace."/js/')," . PHP_EOL;
        }
        $configFileContent = str_replace("{{namespace_module_list}}", $moduleList, $configFileContent);


        //create file
        return FileEditor::createFile(__DIR__ . self::REL_PATH, $configFileContent);
    }






}