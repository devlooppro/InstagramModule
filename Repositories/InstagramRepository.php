<?php

namespace App\Instagram\Repositories;

use App\Instagram\Classes\Post;
use App\Instagram\Classes\Story;
use App\Instagram\Repositories\Contracts\InstagramRepositoryInterface;
use Cache;

class InstagramRepository implements InstagramRepositoryInterface
{
    protected const CACHE_POSTS_KEY = 'instagram_posts';
    protected const CACHE_STORIES_KEY = 'instagram_stories';

    /**
     * @inheritDoc
     */
    public function createPosts(array $posts): array
    {
        /* @var Post $post */
        foreach ($this->getPosts() ?? [] as $post) {
            $post->deleteMedia();
        }

        Cache::forget(self::CACHE_POSTS_KEY);
        Cache::put(self::CACHE_POSTS_KEY, $posts);

        return $posts;
    }

    /**
     * @inheritDoc
     */
    public function createStories(array $stories): array
    {
        /* @var Story $story */
        foreach ($this->getStories() ?? [] as $story) {
            $story->deleteMedia();
        }

        Cache::forget(self::CACHE_STORIES_KEY);
        Cache::put(self::CACHE_STORIES_KEY, $stories);

        return $stories;
    }

    /**
     * @inheritDoc
     */
    public function getPosts(): ?array
    {
        return Cache::get(self::CACHE_POSTS_KEY);
    }

    /**
     * @inheritDoc
     */
    public function getStories(): ?array
    {
        return Cache::get(self::CACHE_STORIES_KEY);
    }
}
