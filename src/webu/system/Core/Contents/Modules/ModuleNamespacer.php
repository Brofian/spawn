<?php

namespace webu\system\Core\Contents\Modules;

class ModuleNamespacer {

    const GLOBAL_NAMESPACE_RAW = "global";


    public static function hashRawNamespace($rawNamespace) {
        return hash("md5", $rawNamespace);
    }


    public static function getGlobalNamespace() {
        return self::hashRawNamespace(self::GLOBAL_NAMESPACE_RAW);
    }

    public static function getGlobalNamespaceRaw() {
        return self::GLOBAL_NAMESPACE_RAW;
    }

}