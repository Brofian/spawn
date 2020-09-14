<?php

namespace webu\system\Core\Base\Custom;

class FileEditor {


    public static function createFolder($path) {
        if(!is_dir($path)) {
            try {
                mkdir($path);
                return true;
            }
            catch(\Exception $e) {
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
    public static function createFile($path, $content = '') : bool {
        try{
            file_put_contents($path, $content);
            return true;
        }
        catch(\Exception $e) {
            return false;
        }
    }

    public static function getFileContent($path) {
        return file_get_contents($path);
    }

    public static function insert($path, $data) {
        file_put_contents($path,$data);
    }

    public static function append($path, $data) {
        file_put_contents($path,$data);
    }



}