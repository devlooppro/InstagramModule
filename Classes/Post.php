<?php

namespace App\Instagram\Classes;

use App\Instagram\Services\DownloadService;
use File;
use Instagram\Model\Media;
use Storage;

class Post
{
    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $thumbnailOriginalSrc;

    /**
     * @var string
     */
    public $thumbnailSrc;

    /**
     * @var string
     */
    public $thumbnailPath;

    /**
     * @var string|null
     */
    public $videoOriginalUrl = null;

    /**
     * @var string|null
     */
    public $videoUrl = null;

    /**
     * @var string|null
     */
    public $videoPath = null;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var bool
     */
    public bool $isVideo;

    /**
     * Post constructor.
     * @param string $userName
     * @param Media $media
     * @param string|null $videoUrl
     */
    public function __construct(string $userName, Media $media, ?string $videoUrl = null)
    {
        $this->userName = $userName;
        $this->thumbnailOriginalSrc = $media->getThumbnailSrc();
        $this->thumbnailPath = (string)DownloadService::download($this->thumbnailOriginalSrc, 'jpg');
        $this->thumbnailSrc = $this->thumbnailPath ? asset(Storage::url($this->thumbnailPath)) : '';
        $this->videoOriginalUrl = $videoUrl;
        $this->videoPath = DownloadService::download($this->videoOriginalUrl, 'mp4');
        $this->videoUrl = $this->videoPath ? asset(Storage::url($this->videoPath)) : null;
        $this->link = $media->getLink();
        $this->description = $media->getCaption();
        $this->isVideo = $media->isVideo();
    }

    public function deleteMedia()
    {
        File::delete(Storage::path($this->thumbnailPath));
        File::delete(Storage::path($this->videoPath));
    }
}
