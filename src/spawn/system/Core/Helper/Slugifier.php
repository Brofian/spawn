<?php

namespace spawn\system\Core\Helper;

class Slugifier {


    public static function slugify(string $subjectString): string {

        $subjectString = str_replace(['_', '-', '/', '\\', '.', ','], ' ', $subjectString);

        $subjectString = ucwords($subjectString);

        $subjectString = str_replace(' ', '', $subjectString);

        return $subjectString;
    }





}