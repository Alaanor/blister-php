<?php

namespace Blister\Model;

use MongoDB\BSON;

class Playlist implements BSON\Serializable
{
    /**
     * @var string $title
     *
     * The title of the playlist
     */
    public string $title;

    /**
     * @var string $author
     *
     * The author of the playlist
     */
    public string $author;

    /**
     * @var string|null $description
     *
     * The description of the playlist, optional
     */
    public ?string $description;

    /**
     * @var string|null $cover
     *
     * Binary data of the cover, can be PNG/JPEG, should be 1:1
     */
    public ?string $cover;

    /**
     * @var Beatmap[] $maps
     *
     * The beatmaps of the playlist
     */
    public array $maps;

    /**
     * @inheritDoc
     */
    public function bsonSerialize()
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'cover' => $this->cover ? new BSON\Binary(base64_decode($this->cover), BSON\Binary::TYPE_GENERIC) : null,
            'maps' => $this->maps,
        ];
    }
}
