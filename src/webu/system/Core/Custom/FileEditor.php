<?php

namespace webu\system\Core\Base\Custom;

use webu\system\Core\Helper\URIHelper;

class FileEditor
{


    public static function createFolder($path)
    {
        //check parent dir recursively
        if(!is_dir(dirname($path))) {
            self::createFolder(dirname($path));
        }

        if (!file_exists($path)) {
            try {
                mkdir($path);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $path
     * @param string $content
     * @return bool
     */
    public static function createFile($path, $content = ''): bool
    {
        self::createFolder(dirname(URIHelper::pathifie($path,"/", false)));

        try {
            file_put_contents($path, $content);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getFileContent($path)
    {
        if(!file_exists($path) || !is_file($path)) {
            return false;
        }

        return file_get_contents($path);
    }

    public static function insert($path, $data)
    {
        self::createFile($path);
        try {
            file_put_contents($path, $data);
            return true;
        }
        catch(\Exception $e) {
            return false;
        }
    }

    public static function append($path, $data)
    {
        self::createFolder(dirname($path));
        try {
            file_put_contents($path, $data, FILE_APPEND);
            return true;
        }
        catch(\Exception $e) {
            return false;
        }

    }


}