<?php


namespace webu\system\Core\Helper;


use Twig\Environment;
use Twig\Loader\ArrayLoader;
use webu\system\Core\Custom\Debugger;

class TwigHelper
{

    private $variables = array();

    private $targetFile = 'index.html.twig';

    private $templateDirs = [
        //default template dir
        ROOT . '\\src\\Resources\\template'
    ];

    private $twig = false;

    public function __construct()
    {
    }

    public function finish() {
        $this->loadTwig();
        $this->startRendering();
    }

    private function loadTwig() {

        $loader = new \Twig\Loader\FilesystemLoader($this->templateDirs);
        $twig = new Environment($loader);

        if(is_object($twig) == false) {
            Debugger::ddump("Couldn´t load Twig");
        }

        $this->twig = $twig;
    }


    private function startRendering() {
        /** Twig $this->twig */
        echo $this->twig->render($this->targetFile, $this->variables);
    }


    public function setRenderFile(string $file) {
        $this->targetFile = $file;
    }

    public function addTemplateDir(string $path) {
        $this->templateDirs[] = $path;
    }



    /**
     * Stores the variables for assigning them to the twig template
     * @param string $key
     * @param string $value
     */
    public function assign(string $key, string $value) {
        $this->variables[$key] = $value;
    }


}