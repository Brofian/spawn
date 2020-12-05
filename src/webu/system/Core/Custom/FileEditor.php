<?php

namespace webu\system\Core\Base\Custom;

class FileEditor
{


    public static function createFolder($path)
    {
        if (!is_dir($path)) {
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
        self::createFolder(dirname($path));

        try {
            file_put_contents($path, $content);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getFileContent($path)
    {
        if(!is_file($path)) {
            return false;
        }

        return file_get_contents($path);
    }

    public static function insert($path, $data)
    {
        self::createFolder(dirname($path));
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