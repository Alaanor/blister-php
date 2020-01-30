<?php

namespace Blister;

use PHPUnit\Framework\TestCase;

class PlaylistLibTest extends TestCase
{
    const validPlaylistPath = __DIR__ . '/../data/valid-playlist.blist';
    const invalidPlaylistPath = __DIR__ . '/../data/invalid-playlist.blist';

    public function testHasMagicNumber()
    {
        $handler = fopen(self::validPlaylistPath, 'r');
        $this->assertTrue(PlaylistLib::HasMagicNumber($handler));
        fclose($handler);

        $handler = fopen(self::invalidPlaylistPath, 'r');
        $this->assertFalse(PlaylistLib::HasMagicNumber($handler));
        fclose($handler);
    }
}
