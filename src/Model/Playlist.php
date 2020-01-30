<?php

namespace Blister\Model;

class Playlist
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
}
