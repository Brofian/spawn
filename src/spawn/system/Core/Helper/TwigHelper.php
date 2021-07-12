<?php declare(strict_types=1);


namespace spawn\system\Core\Helper;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use spawn\system\Core\Contents\Collection\AssociativeCollection;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Custom\Debugger;
use spawn\system\Core\Extensions\ExtensionLoader;
use spawn\system\Core\Services\ServiceContainerProvider;

class TwigHelper
{
    const CACHE_FOLDER_PATH = ROOT . '/var/cache/private/twig';

    protected string $targetFile = 'base.html.twig';
    protected array $templateDirs = array();
    protected ?string $customoutput = null;
    protected Environment $twig;
    protected AssociativeCollection $context;


    public function __construct()
    {
        $this->context = new AssociativeCollection();
    }

    /**
     * @param string $filePath
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderFile(string $filePath): string {
        if(!isset($this->twig)) {
            $this->loadTwig();
        }
        return $this->twig->render($filePath, $this->context->getArray());
    }


    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @return string
     */
    public function finish() : string {
        if(!isset($this->twig)) {
            $this->loadTwig();
        }

        return $this->startRendering();
    }

    protected function loadTwig() {
        $moduleCollection = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.modules.collection');
        $this->loadTemplateDirFromModuleCollection($moduleCollection);

        $loader = new FilesystemLoader($this->templateDirs);
        $twig = new Environment($loader, [
            'debug' => (MODE == "dev"),
            'cache' => self::CACHE_FOLDER_PATH,
        ]); //<- Twig environment


        if(is_object($twig) == false) {
            Debugger::ddump("Couldn´t load Twig");
        }

        ExtensionLoader::loadTwigExtensions($twig);
        $twig->addExtension(new DebugExtension());

        $this->twig = $twig;
    }

    /**
     * Executes the twig rendering
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function startRendering() : string {

        //check customoutput
        if($this->customoutput !== null) {
            return $this->customoutput;
        }

        return $this->twig->render($this->targetFile, $this->context->getArray());
    }


    public function setRenderFile(string $file): self {
        $this->targetFile = $file;
        return $this;
    }


    public function addTemplateDir(string $path): self {
        $this->templateDirs[] = URIHelper::pathifie($path);
        return $this;
    }


    public function assign(string $key, $value): self {
        $this->context->set($key, $value);
        return $this;
    }


    public function setOutput($value): self {
        if(is_string($value)) {
            $this->customoutput = $value;
        }
        else {
            $this->customoutput = json_encode($value);
        }

        return $this;
    }


    public function loadTemplateDirFromModuleCollection(ModuleCollection $moduleCollection) {
        $moduleList = $moduleCollection->getModuleList();
        ModuleCollection::sortModulesByWeight($moduleList);

        foreach($moduleList as $module) {
            $this->addTemplateDir(ROOT . $module->getResourcePath() . "/template");
        }
    }
}