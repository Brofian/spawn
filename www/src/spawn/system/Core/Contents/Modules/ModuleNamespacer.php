<?php declare(strict_types=1);

namespace spawn\system\Core\Contents\Modules;

/*
 * Currently unused
 */
class ModuleNamespacer {

    public const GLOBAL_NAMESPACE_RAW = "global";

    /**
     * @param $rawNamespace
     * @return string
     */
    public static function hashNamespace($rawNamespace)
    {
        return hash("md5", $rawNamespace);
    }

}