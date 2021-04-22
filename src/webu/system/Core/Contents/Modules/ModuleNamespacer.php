<?php

namespace webu\system\Core\Contents\Modules;

class ModuleNamespacer {

    const GLOBAL_NAMESPACE_RAW = "global";

    /**
     * @param $rawNamespace
     * @return string
     */
    public static function hashRawNamespace($rawNamespace) {
        return hash("md5", $rawNamespace);
    }

    /**
     * @return string
     */
    public static function getGlobalNamespace() {
        return self::hashRawNamespace(self::GLOBAL_NAMESPACE_RAW);
    }

    /**
     * @return string
     */
    public static function getGlobalNamespaceRaw() {
        return self::GLOBAL_NAMESPACE_RAW;
    }

}