<?php


namespace webu\system\Core\Base\Custom;

/**
 * Class FileCrawler
 * @package webu\autoloader
 *
 * Allows you to simply check all files in a directory.
 * The Check function has to have the following Parameters:
 * - string (the content of the file)
 * - &array (the current array of results)
 * - string (the name of the currently selected file)
 * - string (the relative path the currently selected file from the root)
 */
class FileCrawler
{

    /** @var callable $checkFunction */
    public $checkFunction;
    /** @var array $results */
    private $results = array();
    /** @var int */
    private $maxDepth = 999;


    /**
     * Searches in all files in the given path and its sub directories
     * @param string $rootPath
     * @param callable $checkFunction
     * @return array
     */
    public function searchInfos(string $rootPath, callable $checkFunction, int $maxDepth = 999): array
    {

        if (is_callable($checkFunction) == false) return [];
        $this->checkFunction = $checkFunction;

        $this->maxDepth = $maxDepth;

        $this->scanDirs($rootPath, $this->results);

        return $this->results;
    }

    /**
     * Loads all classes in the directory
     * @param string $current
     * @param array $classes
     * @return array
     */
    private function scanDirs(string $current, array &$ergs, int $depth = 0): array
    {
        $currentContents = scandir($current);

        foreach ($currentContents as $content) {
            //skip relative folders and cache
            if ($content == '..' || $content == '.' || $content == 'cache') continue;
            //skip invisible folders
            if (substr($content, 0, 1) == '.') continue;


            //extend path with current content element
            $path = $current . '\\' . $content;

            //check if content is file or directory
            if (is_file($path)) {

                //if class: load to classes array
                $fileContent = file_get_contents($path);

                $function = $this->checkFunction;
                $function($fileContent, $ergs, $content, $path);

            } else if (is_dir($path) && $depth < $this->maxDepth) {
                //if class is another dir, scan it
                $this->scanDirs($path, $ergs, $depth + 1);

            }

        }

        return $ergs;
    }

}