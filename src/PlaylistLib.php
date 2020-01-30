<?php

namespace Blister;

use Blister\Model\Beatmap;
use Blister\Model\BeatmapHash;
use Blister\Model\BeatmapKey;
use Blister\Model\BeatmapTypes;
use Blister\Model\Playlist;
use PHPUnit\Framework\Exception;
use MongoDB\BSON;
use DateTime;

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

    /**
     * Deserialize the resource as a blister playlist
     *
     * @param string $filepath
     * @return Playlist
     */
    public static function Deserialize($filepath): Playlist
    {
        $handler = fopen($filepath, 'r');

        if (!self::HasMagicNumber($handler)) {
            throw new InvalidMagicNumber();
        }

        fseek($handler, 0);
        $data = fread($handler, filesize($filepath));
        $gzipData = substr($data, strlen(self::$magicNumber));
        $uncompressed = gzdecode($gzipData);
        $bson = BSON\toPHP($uncompressed, array());
        return self::MapToPlaylist($bson);
    }

    /**
     * Map the bson php object to a playlist
     *
     * @param $bson
     * @return Playlist
     */
    private static function MapToPlaylist($bson): Playlist
    {
        $playlist = new Playlist();
        $playlist->title = $bson->title;
        $playlist->author = $bson->author;
        $playlist->description = $bson->description;
        $playlist->cover = base64_encode($bson->cover->getData());
        $playlist->maps = [];

        foreach ($bson->maps as $beatmap) {
            $playlist->maps[] = self::MapToBeatmap($beatmap);
        }

        return $playlist;
    }

    /**
     * Map the bson php object to a beatmap
     *
     * @param $bsonBeatmap
     * @return Beatmap
     */
    private static function MapToBeatmap($bsonBeatmap): Beatmap
    {
        switch ($bsonBeatmap->type) {
            case BeatmapTypes::Key:
                $beatmap = new BeatmapKey();
                $beatmap->key = bin2hex($bsonBeatmap->key);
                break;
            case BeatmapTypes::Hash:
                $beatmap = new BeatmapHash();
                $beatmap->hash = bin2hex($bsonBeatmap->hash);
                break;

            case BeatmapTypes::Zip:
            case BeatmapTypes::LevelId:
                throw new UnsupportedBeatmapFormat();

            default:
                throw new Exception('Unexpected value');
        }

        $time = (int)(((int)(string)$bsonBeatmap->dateAdded) / 1000);
        $beatmap->dateAdded = DateTime::createFromFormat("U", $time);

        return $beatmap;
    }
}

class InvalidMagicNumber extends Exception
{
}

class UnsupportedBeatmapFormat extends Exception
{
}
