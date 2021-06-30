<?php

namespace webu\system\Core\Services;

class ServiceProperties {

    const _ID = 'id';
    const _CLASS = 'class';
    const _STATIC = 'static';
    const _INSTANCE = 'instance';
    const _DECORATES = 'decorates';
    const _PARENT = 'parent';
    const _ABSTRACT = 'abstract';
    const _TAG = 'tag';
    const _MODULE_ID = 'module_id';

    public static function getPropertyList(): array {
        $oClass = new \ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    public static function getPropertyGetterMethods(): array {
        $getterMethods = [];
        foreach(self::getPropertyList() as $property => $value) {
            $getMethodName = str_replace('_','', ucwords('get_'.$value, '_'));
            $getterMethods[$value] = $getMethodName;
        }
        return $getterMethods;
    }

    public static function getPropertySetterMethods(): array {
        $getterMethods = [];
        foreach(self::getPropertyList() as $property => $value) {
            $getMethodName = str_replace('_','', ucwords('set_'.$value, '_'));
            $getterMethods[$value] = $getMethodName;
        }
        return $getterMethods;
    }



}