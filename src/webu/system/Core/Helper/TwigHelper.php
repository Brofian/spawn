<?php


namespace webu\system\Core\Helper;


use Twig\Environment;
use Twig\Loader\ArrayLoader;
use webu\system\Core\Custom\Debugger;

class TwigHelper
{

    private $variables = array();

    private $targetFile = '';

    private $twig = false;

    public function __construct()
    {
        $this->loadTwig();

        $this->assign("name", "Fabian");
        $this->startRendering();
    }


    private function loadTwig() {
        $loader = new \Twig\Loader\FilesystemLoader(ROOT.'\\src\\Template\\Resources');
        $twig = new Environment($loader);

        if(is_object($twig) == false) {
            Debugger::ddump("Couldn´t load Twig");
        }

        $this->twig = $twig;
    }


    public function startRendering() {
        echo $this->twig->render('index.html.twig', $this->variables);
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