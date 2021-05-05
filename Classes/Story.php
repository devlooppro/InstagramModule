<?php

namespace App\Instagram\Classes;

use App\Instagram\Services\DownloadService;
use File;
use Instagram\Model\StoryMedia;
use Storage;

class Story
{
    public int $id;
    public ?string $displayOriginalUrl = null;
    public ?string $displayUrl = null;
    public ?string $displayPath = null;
    public ?string $videoOriginalUrl = null;
    public ?string $videoUrl = null;
    public ?string $videoPath = null;
    public float $videoDuration = 0;
    public ?string $mention = null;

    /**
     * Post constructor.
     * @param StoryMedia $story
     */
    public function __construct(StoryMedia $story)
    {
        $this->id = $story->getId();

        $this->displayOriginalUrl = $story->getDisplayUrl();
        $this->displayPath = DownloadService::download($this->displayOriginalUrl, 'jpg');
        $this->displayUrl = $this->displayPath ? asset(Storage::url($this->displayPath)) : null;

        $videResources = $story->getVideoResources();
        $videResource = reset($videResources);

        $this->videoOriginalUrl = $videResource ? $videResource->src : null;
        $this->videoPath = DownloadService::download($this->videoOriginalUrl, 'mp4');
        $this->videoUrl = $this->videoPath ? asset(Storage::url($this->videoPath)) : null;

        $this->videoDuration = $story->getVideoDuration();
        $mentions = $story->getMentions();
        $this->mention = is_array($mentions) ? reset($mentions) : null;
    }

    public function deleteMedia()
    {
        File::delete(Storage::path($this->displayPath));
        File::delete(Storage::path($this->videoPath));
    }
}
