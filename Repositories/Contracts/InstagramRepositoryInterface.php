<?php

namespace App\Instagram\Repositories\Contracts;

use App\Instagram\Classes\Post;
use App\Instagram\Classes\Story;

interface InstagramRepositoryInterface
{
    /**
     * @param Post[] $posts
     * @return Post[]
     */
    public function createPosts(array $posts): array;

    /**
     * @param Story[] $stories
     * @return Story[]
     */
    public function createStories(array $stories): array;

    /**
     * @return Post[]|null
     */
    public function getPosts(): ?array;

    /**
     * @return Story[]|null
     */
    public function getStories(): ?array;
}
