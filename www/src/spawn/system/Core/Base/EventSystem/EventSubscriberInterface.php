<?php

namespace spawn\system\Core\Base\EventSystem;

interface EventSubscriberInterface {

    public static function getSubscribedEvents(): array;

}