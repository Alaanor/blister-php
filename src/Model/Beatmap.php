<?php

namespace Blister\Model;

use DateTime;
use MongoDB\BSON;

abstract class Beatmap implements BSON\Serializable
{
    /**
     * @var int $type
     *
     * Type of the beatmap, should be one of the value provided by BeatmapTypes
     */
    public int $type;

    /**
     * @var DateTime $dateAdded
     *
     * The datetime when the map has been added to the playlist.
     */
    public DateTime $dateAdded;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return [
            'type' => $this->type,
            'dateAdded' => new BSON\UTCDateTime($this->dateAdded)
        ];
    }
}

class BeatmapKey extends Beatmap
{
    public int $type = BeatmapTypes::Key;

    /**
     * @var int $key
     *
     * The key of the beatmap on beatsaver
     */
    public int $key;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return array_merge(
            parent::bsonSerialize(),
            ['key' => hexdec($this->key)]
        );
    }
}

class BeatmapHash extends Beatmap
{
    public int $type = BeatmapTypes::Hash;

    /**
     * @var string $hash
     *
     * The hash of the beatmap
     */
    public string $hash;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return array_merge(
            parent::bsonSerialize(),
            ['hash' => new BSON\Binary(hex2bin($this->hash), BSON\Binary::TYPE_GENERIC)]
        );
    }
}

class BeatmapZip extends Beatmap
{
    public int $type = BeatmapTypes::Zip;

    /**
     * @var string $bytes
     *
     * The bytes of the beatmap zip
     */
    public string $bytes;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return array_merge(
            parent::bsonSerialize(),
            ['bytes' => new BSON\Binary($this->bytes, BSON\Binary::TYPE_GENERIC)]
        );
    }
}

class BeatmapLevelId extends Beatmap
{
    public int $type = BeatmapTypes::LevelId;

    /**
     * @var string $levelId
     *
     * The levelId of the beatmap
     */
    public string $levelId;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return array_merge(
            parent::bsonSerialize(),
            ['levelId' => $this->levelId]
        );
    }
}

abstract class BeatmapTypes
{
    public const Key = 0;
    public const Hash = 1;
    public const Zip = 2;
    public const LevelId = 3;
}
