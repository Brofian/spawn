<?php

namespace spawn\system\Core\Custom;

class ClassInspector extends Mutable {

    protected string $class = '';
    protected array $methods = [];

    public function __construct(string $class, callable $methodFilter = null)
    {
        $this->class = $class;
        $this->loadMethods($methodFilter);
    }

    /**
     * @return MethodInspector[]
     */
    public function getLoadedMethods(): array {
        return $this->methods;
    }


    protected function loadMethods(?callable $methodFilter = null): void {
        try {
            $reflectionClass = new \ReflectionClass($this->class);
            $methods = $reflectionClass->getMethods();

            foreach($methods as $method) {
                $inspectedMethod = new MethodInspector($this->class, $method->getName());

                if($methodFilter === null || ($methodFilter !== null && $methodFilter($inspectedMethod))) {
                    $this->methods[] = $inspectedMethod;
                }
            }
        }
        catch(\Exception $e) {
            $this->methods = [];
            return;
        }
    }


}