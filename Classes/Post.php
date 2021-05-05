<?php

namespace App\Instagram\Classes;

use App\Instagram\Services\DownloadService;
use File;
use Instagram\Model\Media;
use Storage;

class Post
{
    public string $userName;
    public string $thumbnailOriginalSrc;
    public string $thumbnailSrc;
    public string $thumbnailPath;
    public ?string $videoOriginalUrl = null;
    public ?string $videoUrl = null;
    public ?string $videoPath = null;
    public string $link;
    public ?string $description;
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
