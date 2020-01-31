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
     * @param string $data
     * @return bool
     */
    public static function HasMagicNumber($data): bool
    {
        return substr($data, 0, strlen(self::$magicNumber)) === self::$magicNumber;
    }

    /**
     * Deserialize the filepath as a blister playlist
     *
     * @param string $filepath
     * @return Playlist
     */
    public static function DeserializePath($filepath): Playlist
    {
        $data = file_get_contents($filepath);
        return self::Deserialize($data);
    }

    /**
     * @param string $data
     * @return Playlist
     */
    public static function Deserialize($data): Playlist
    {
        if (!self::HasMagicNumber($data)) {
            throw new InvalidMagicNumber();
        }

        try {
            $gzipData = substr($data, strlen(self::$magicNumber));
            $uncompressed = gzdecode($gzipData);
            $bson = BSON\toPHP($uncompressed, array());
            return self::MapToPlaylist($bson);
        } catch (Exception $e) {
            throw new InvalidPlaylistFormat("Failed to deserialize", 0, $e);
        }
    }

    /**
     * Serialize a playlist object using the blister format
     *
     * @param Playlist $playlist
     * @return string
     */
    public static function Serialize(Playlist $playlist): string
    {
        return self::$magicNumber . gzencode(BSON\fromPHP($playlist));
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
        $playlist->cover = base64_encode((string)$bson->cover);
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
                $beatmap->key = dechex($bsonBeatmap->key);
                break;
            case BeatmapTypes::Hash:
                $beatmap = new BeatmapHash();
                $beatmap->hash = bin2hex($bsonBeatmap->hash);
                break;

            case BeatmapTypes::Zip:
            case BeatmapTypes::LevelId:
            default:
                throw new UnsupportedBeatmapFormat();
        }

        try {
            $msTimeStamps = (int)(((int)(string)$bsonBeatmap->dateAdded) / 1000);
            $beatmap->dateAdded = DateTime::createFromFormat("U", $msTimeStamps);
        } catch (\Exception $e) {
            print_r($bsonBeatmap);
            throw new FailedToParseBeatmapDateTime('Failed to parse dateAdded field', 0, $e);
        }

        return $beatmap;
    }
}

class InvalidMagicNumber extends Exception
{
}

class UnsupportedBeatmapFormat extends Exception
{
}

class InvalidPlaylistFormat extends Exception
{
}

class FailedToParseBeatmapDateTime extends Exception
{
}