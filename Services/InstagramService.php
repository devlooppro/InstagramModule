<?php

namespace App\Instagram\Services;

use App\Instagram\Classes\Post;
use App\Instagram\Classes\Story;
use App\Instagram\Repositories\Contracts\InstagramRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;
use Instagram\Api as InstagramApi;
use Instagram\Exception\InstagramAuthException;
use Instagram\Exception\InstagramException;
use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Media;
use Instagram\Model\Profile as InstagramProfile;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class InstagramService
{
    /**
     * @var InstagramApi
     */
    protected $api;

    /**
     * @var InstagramProfile
     */
    protected $profile;

    /**
     * @var InstagramRepositoryInterface
     */
    protected $repository;

    /**
     * InstagramService constructor.
     * @throws BindingResolutionException
     * @throws GuzzleException
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->repository = app()->make(InstagramRepositoryInterface::class);

        $cache_path = storage_path('instagram/cache');
        if (!File::exists($cache_path)) {
            File::makeDirectory($cache_path, 0755, true);
        }
        $cachePool = new FilesystemAdapter('Instagram', 0, $cache_path);
        $this->api = new  InstagramApi($cachePool);
        $this->api->login(config('instagram.login'), config('instagram.password'));
        $username = config('instagram.profile');
        $this->profile = $this->api->getProfile($username);
    }

    /**
     * @return Post[]
     * @throws InstagramAuthException
     * @throws InstagramFetchException
     */
    public function updatePosts(): array
    {
        $posts = [];

        foreach ($this->profile->getMedias() as $media) {
            $videoUrl = null;
            if ($media->isVideo()) {
                $videoUrl = $this->getVideoUrl($media);
            }
            $posts[] = new Post($this->profile->getUserName(), $media, $videoUrl);
        }

        return $this->repository->createPosts($posts);
    }

    /**
     * @return Story[]
     * @throws InstagramAuthException
     * @throws InstagramFetchException
     */
    public function updateStories(): array
    {
        $iFolders = $this->api->getStoryHighlightsFolder($this->profile->getId())->getFolders();
        $storiesPerFolder = config('instagram.max-stories-per-folder', 10);
        /* @var Story[] $stories */
        $stories = [];

        foreach ($iFolders as $iFolder) {
            if (empty(config('instagram.download-folders')) || in_array($iFolder->getName(), config('instagram.download-folders'))) {
                sleep(1);
                $folder = $this->api->getStoriesOfHighlightsFolder($iFolder);

                $i = 0;
                foreach ($folder->getStories() as $story) {
                    $stories[] = new Story($story);

                    $i++;
                    if ($i == $storiesPerFolder) {
                        break;
                    }
                }
            }
        }

        return $this->repository->createStories($stories);
    }

    /**
     * @param Media $media
     * @return string|null
     * @throws InstagramAuthException
     * @throws InstagramFetchException
     */
    protected function getVideoUrl(Media $media): ?string
    {
        return $this->api->getMediaDetailedByShortCode($media)->getVideoUrl();
    }
}
