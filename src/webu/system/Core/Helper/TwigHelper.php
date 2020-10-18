<?php


namespace webu\system\Core\Helper;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use webu\system\Core\Custom\Debugger;

class TwigHelper
{

    /** @var array  */
    private $variables = array();

    /** @var string  */
    private $targetFile = 'index.html.twig';

    /** @var array  */
    private $templateDirs = [
        //default template dir
        ROOT . '\\src\\Resources\\template'
    ];

    /** @var string|null  */
    private $customoutput = null;

    /** @var bool  */
    private $twig = false;

    public function __construct()
    {
    }

    /**
     * This function is called after executing the controllers
     * @return void
     */
    public function finish() {
        $this->loadTwig();
        $this->startRendering();
    }

    /**
     * Load the Twig Instance with the registeres templateDirs
     * @return void
     */
    private function loadTwig() {

//        $this->templateDirs = array_reverse($this->templateDirs);

        $loader = new FilesystemLoader($this->templateDirs);
        $twig = new Environment($loader); //<- Twig environment

        if(is_object($twig) == false) {
            Debugger::ddump("Couldn´t load Twig");
        }

        $this->twig = $twig;
    }

    /**
     * Executes the twig rendering
     * @return void
     */
    private function startRendering() {

        //check customoutout
        if($this->customoutput !== null) {
            echo $this->customoutput;
            return;
        }

        /** @var Environment $twig */
        $twig = $this->twig;
        echo $twig->render($this->targetFile, $this->variables);
        return;
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


    public function setOutput($value) {
        if(is_string($value)) {
            $this->customoutput = $value;
        }
        else {
            $this->customoutput = json_encode($value);
        }
    }

}