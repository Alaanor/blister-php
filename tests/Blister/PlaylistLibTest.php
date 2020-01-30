<?php

namespace Blister;

use Blister\Model\BeatmapTypes;
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
    
    public function testDeserialize() {
        $playlist = PlaylistLib::Deserialize(self::validPlaylistPath);

        $this->assertEquals("Test", $playlist->title);
        $this->assertEquals("Alaanor", $playlist->author);
        $this->assertEquals("That's just a test", $playlist->description);
        $this->assertStringStartsWith("/9j/4", $playlist->cover);

        $this->assertEquals(1, sizeof($playlist->maps));
        $this->assertEquals(BeatmapTypes::Hash, $playlist->maps[0]->type);
        $this->assertEquals("01fb2aa5064d8e30105de66181be1b3fbc9fa28a", $playlist->maps[0]->hash);
    }
}
