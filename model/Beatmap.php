<?php

namespace Blister {

    use DateTime;

    abstract class Beatmap
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
    }

    abstract class BeatmapTypes
    {
        public const Key = 0;
        public const Hash = 1;
        public const Zip = 2;
        public const LevelId = 3;
    }
}
