<?php declare(strict_types=1);

namespace webu\system\Core\Helper\FrameworkHelper;


use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Base\Database\DatabaseTable;

class DatabaseStructureHelper {

    const STRUCTURE_FILE_NAMESPACE = "webu\\Database\\StructureTables";
    const CACHE_DIRECTORY = ROOT . "\\dev\\database\\tables\\";

    /**
     * Creates classes for the given tables
     * @param DatabaseTable $table
     */
    public static function createDatabaseStructure(DatabaseTable $table)
    {
        $folderName = self::CACHE_DIRECTORY;

        $tablename = $table->getTableName();

        $filecontent =
            "<?php" . PHP_EOL . PHP_EOL .
            "namespace " . self::STRUCTURE_FILE_NAMESPACE . ";" . PHP_EOL . PHP_EOL .
            "class " . toClassnameFormat($tablename) . " {" . PHP_EOL . PHP_EOL .
            "\tconst _TABLENAME_RAW = '" . $tablename . "';" . PHP_EOL .
            "\tconst TABLENAME = '`" . $tablename . "`';" . PHP_EOL;

        foreach ($table->getColumnNames() as $columnName) {
            $filecontent .= "\tconst RAW_COL_" . strtoupper($columnName) . " = '" . $columnName . "';" . PHP_EOL;
            $filecontent .= "\tconst COL_" . strtoupper($columnName) . " = '`" . $tablename . "`.`" . $columnName . "`';" . PHP_EOL;
        }

        $filecontent .= '}';

        $fileName = $folderName . toClassnameFormat($tablename) . '.php';
        FileEditor::createFolder(dirname($fileName));
        FileEditor::createFile($fileName, $filecontent);
    }


}