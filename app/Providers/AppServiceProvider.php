<?php

namespace App\Providers;

use App\Repositories\Authentication\OAuthClientRepository;
use App\Repositories\Authentication\OAuthClientRepositoryInterface;
use App\Repositories\Authentication\ResetPasswordTokenRepository;
use App\Repositories\Authentication\ResetPasswordTokenRepositoryInterface;
use App\Repositories\Authentication\UserRepository;
use App\Repositories\Authentication\UserRepositoryInterface;
use App\Repositories\Authentication\UserVerifyTokenRepository;
use App\Repositories\Authentication\UserVerifyTokenRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Manga\FavoriteRepository;
use App\Repositories\Manga\FavoriteRepositoryInterface;
use App\Repositories\Manga\NewFeedRepository;
use App\Repositories\Manga\NewFeedRepositoryInterface;
use App\Repositories\Manga\CategoryRepository;
use App\Repositories\Manga\CategoryRepositoryInterface;
use App\Repositories\Manga\ChapterRepository;
use App\Repositories\Manga\ChapterRepositoryInterface;
use App\Repositories\Manga\DownloadRepository;
use App\Repositories\Manga\DownloadRepositoryInterface;
use App\Repositories\Manga\MangaRepository;
use App\Repositories\Manga\MangaRepositoryInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserVerifyTokenRepositoryInterface::class, UserVerifyTokenRepository::class);
        $this->app->bind(ResetPasswordTokenRepositoryInterface::class, ResetPasswordTokenRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(MangaRepositoryInterface::class, MangaRepository::class);
        $this->app->bind(ChapterRepositoryInterface::class, ChapterRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind(DownloadRepositoryInterface::class, DownloadRepository::class);
        $this->app->bind(NewFeedRepositoryInterface::class, NewFeedRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
