<?php

namespace spawn\system\Core\Helper;

class UUID
{

    public const UUID_LENGTH = 24;
    public const UUID_PATTERN = '/^([a-f0-9]*)$/m';

    public static function randomBytes(): string
    {
        return random_bytes(self::UUID_LENGTH);
    }

    public static function randomHex(): string {
        return self::bytesToHex(self::randomBytes());
    }

    public static function hexToBytes(string $hex): string
    {
        return hex2bin($hex);
    }

    public static function bytesToHex(string $bytes): string
    {
        return bin2hex($bytes);
    }

    public static function validateUUID($uuid): bool
    {
        $uuidLength = strlen($uuid);
        if ($uuidLength == self::UUID_LENGTH) {
            $uuid = self::bytesToHex($uuid);
        } else if ($uuidLength != (2 * self::UUID_LENGTH)) {
            return false;
        }

        return (bool)preg_match(self::UUID_PATTERN, $uuid);
    }


}