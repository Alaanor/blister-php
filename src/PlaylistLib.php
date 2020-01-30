<?php

namespace Blister;

class PlaylistLib
{
    public static string $magicNumber = "Blist.v2";

    /**
     * Check if the file has the magic number
     *
     * @param resource $handler
     * @return bool
     */
    public static function HasMagicNumber($handler): bool
    {
        fseek($handler, 0);
        return fread($handler, strlen(self::$magicNumber)) === self::$magicNumber;
    }
}
