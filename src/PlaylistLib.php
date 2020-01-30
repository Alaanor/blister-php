<?php

namespace Blister;

class PlaylistLib
{
    public string $magicNumber = "Blist.v2";

    /**
     * Check if the file has the magic number
     *
     * @param resource $handler
     * @return bool
     */
    public function HasMagicNumber($handler): bool
    {
        fseek($handler, 0);
        return fread($handler, strlen($this->magicNumber)) === $this->magicNumber;
    }
}
