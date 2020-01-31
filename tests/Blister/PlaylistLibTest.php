<?php

namespace Blister;

use Blister\Model\BeatmapKey;
use Blister\Model\BeatmapHash;
use Blister\Model\BeatmapZip;
use Blister\Model\BeatmapLevelId;
use Blister\Model\BeatmapTypes;
use Blister\Model\Playlist;
use DateTime;
use PHPUnit\Framework\TestCase;

class PlaylistLibTest extends TestCase
{
    const validPlaylistPath = __DIR__ . '/../data/valid-playlist.blist';
    const invalidPlaylistPath = __DIR__ . '/../data/invalid-playlist.blist';

    public function testHasMagicNumber()
    {
        $rawData = file_get_contents(self::validPlaylistPath);
        $this->assertTrue(PlaylistLib::HasMagicNumber($rawData));

        $rawData = file_get_contents(self::invalidPlaylistPath);
        $this->assertFalse(PlaylistLib::HasMagicNumber($rawData));
    }
    
    public function testDeserialize() {
        $playlist = PlaylistLib::DeserializePath(self::validPlaylistPath);

        $this->assertEquals("Test", $playlist->title);
        $this->assertEquals("Alaanor", $playlist->author);
        $this->assertEquals("That's just a test", $playlist->description);
        $this->assertStringStartsWith("/9j/4", $playlist->cover);

        $this->assertEquals(1, sizeof($playlist->maps));
        $this->assertEquals(BeatmapTypes::Hash, $playlist->maps[0]->type);
        $this->assertEquals("01fb2aa5064d8e30105de66181be1b3fbc9fa28a", $playlist->maps[0]->hash);
        $this->assertEquals(2019, $playlist->maps[0]->dateAdded->format("Y"));
    }
    
    public function testSerialize() {
        $sourcePlaylist = PlaylistLib::DeserializePath(self::validPlaylistPath);
        $serialized = PlaylistLib::Serialize($sourcePlaylist);
        $serializedDeserialized = PlaylistLib::Deserialize($serialized);

        $this->assertEquals($sourcePlaylist, $serializedDeserialized);
    }

    public function testAllBeatmapsType() {
        $key = "1665";
        $hash = "d03257e1541d4e6381acca614dca5225d62fa1e4";
        $zip = "byte";
        $lid = "1234";

        $playlist = new Playlist();
        $playlist->title = 'test';
        $playlist->author = 'phpunit';
        $playlist->description = 'just a test';
        $playlist->cover = 'byte';
        
        $beatmapKey = new BeatmapKey();
        $beatmapKey->dateAdded = new DateTime();
        $beatmapKey->key = $key;

        $beatmapHash = new BeatmapHash();
        $beatmapHash->dateAdded = new DateTime();
        $beatmapHash->hash = $hash;

        $beatmapZip = new BeatmapZip();
        $beatmapZip->dateAdded = new DateTime();
        $beatmapZip->bytes = $zip;

        $beatmapLevelId = new BeatmapLevelId();
        $beatmapLevelId->dateAdded = new DateTime();
        $beatmapLevelId->levelId = $lid;
        
        $playlist->maps = [
            $beatmapKey,
            $beatmapHash,
            $beatmapZip,
            $beatmapLevelId,
        ];

        $serialized = PlaylistLib::Serialize($playlist);
        $deserialized = PlaylistLib::Deserialize($serialized);

        $this->assertEquals('test', $deserialized->title);
        $this->assertEquals('phpunit', $deserialized->author);
        $this->assertEquals('just a test', $deserialized->description);
        $this->assertEquals('byte', $deserialized->cover);

        $this->assertEquals(4, sizeof($deserialized->maps));
        $this->assertEquals(BeatmapTypes::Key, $deserialized->maps[0]->type);
        $this->assertEquals(BeatmapTypes::Hash, $deserialized->maps[1]->type);
        $this->assertEquals(BeatmapTypes::Zip, $deserialized->maps[2]->type);
        $this->assertEquals(BeatmapTypes::LevelId, $deserialized->maps[3]->type);

        $this->assertEquals($key, $deserialized->maps[0]->key);
        $this->assertEquals($hash, $deserialized->maps[1]->hash);
        $this->assertEquals($zip, $deserialized->maps[2]->bytes);
        $this->assertEquals($lid, $deserialized->maps[3]->levelId);
    }
}
