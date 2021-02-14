<?php


namespace webu\system\Core\Helper;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Node\Expression\FunctionExpression;
use Twig\TwigFunction;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Extensions\ExtensionLoader;
use webu\system\Core\Extensions\Twig\LinkExtension;

class TwigHelper
{

    /** @var array  */
    private $variables = array();

    /** @var string  */
    private $targetFile = 'base.html.twig';

    /** @var array  */
    private $templateDirs = [
        //default template dir
        ROOT . '\\src\\Resources\\template'
    ];

    /** @var string|null  */
    private $customoutput = null;

    /** @var bool  */
    private $twig = false;

    private $cacheFolderPath = ROOT . '/var/cache/twig';

    public function __construct()
    {
        $this->alwaysReload = (MODE == 'dev');
    }

    /**
     * This function is called after executing the controllers
     * @return string
     */
    public function finish() : string {

        $this->loadTwig();
        try {
            return $this->startRendering();
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Load the Twig Instance with the registeres templateDirs
     * @return void
     */
    private function loadTwig() {


        $loader = new FilesystemLoader($this->templateDirs);
        $twig = new Environment($loader, [
            'debug' => (MODE == "dev"),
            'cache' => $this->cacheFolderPath,
        ]); //<- Twig environment


        ExtensionLoader::loadTwigExtensions($twig);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new LinkExtension(MAIN_ADDRESS_FULL));

        if(is_object($twig) == false) {
            Debugger::ddump("Couldn´t load Twig");
        }

        $this->twig = $twig;
    }

    /**
     * Executes the twig rendering
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function startRendering() : string {

        //check customoutout
        if($this->customoutput !== null) {
            return $this->customoutput;
        }

        /** @var Environment $twig */
        $twig = $this->twig;
        return $twig->render($this->targetFile, $this->variables);
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
     * @param mixed $value
     */
    public function assign(string $key, $value) {
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