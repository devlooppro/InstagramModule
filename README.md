# Instagram media

### Installation

After cloning the repository to app directory in your project.

Run the following commands
```sh
$ composer require pgrimaud/instagram-user-feed
$ php artisan storage:link
```

Add `App\Instagram\Providers\InstagramServiceProvider::class` to providers in config/app.php

To download/update posts and stories from instagram write:
```
$service = new InstagramService();
$service->updatePosts();
$service->updateStories();
```

For use:
```
/* @var InstagramRepositoryInterface $repository */
$repository = app(InstagramRepositoryInterface::class);
$instagramPosts = $repository->getPosts();
$instagramStories = $repository->getStories();
```
