<?php

use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\AuthSessionController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MemeController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\UiPreferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/ui/locale/{locale}', [UiPreferenceController::class, 'locale'])->name('locale.switch');
Route::get('/ui/theme/{theme}', [UiPreferenceController::class, 'theme'])->name('theme.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('pages.about');

Route::match(['GET', 'POST'], '/register', [AuthSessionController::class, 'register'])->name('register');
Route::match(['GET', 'POST'], '/login', [AuthSessionController::class, 'login'])->name('login');
Route::post('/logout', [AuthSessionController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::get('/account/profile', [ProfileController::class, 'edit'])->name('account.profile');
    Route::patch('/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');

    Route::get('/account/permissions', [AccountController::class, 'permissions'])->name('account.permissions');

    Route::get('/admin/roles', [AdminRoleController::class, 'index'])
        ->middleware('permission:role.manage')
        ->name('admin.roles');

    Route::get('/admin/statistics', StatisticsController::class)
        ->middleware('permission:analytics.view')
        ->name('admin.statistics');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->middleware('permission:user.view')
        ->name('admin.users');
    Route::post('/admin/users/{user}/ban', [AdminUserController::class, 'ban'])
        ->middleware('permission:user.ban')
        ->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [AdminUserController::class, 'unban'])
        ->middleware('permission:user.ban')
        ->name('admin.users.unban');

    Route::get('/memes/create', [MemeController::class, 'create'])
        ->middleware('permission:meme.create')
        ->name('memes.create');
    Route::post('/memes', [MemeController::class, 'store'])
        ->middleware('permission:meme.create')
        ->name('memes.store');

    Route::get('/memes/mine', [MemeController::class, 'mine'])->name('memes.mine');
    Route::get('/moderation', [MemeController::class, 'moderation'])
        ->middleware('permission:meme.publish')
        ->name('memes.moderation');

    Route::get('/memes/{meme}/edit', [MemeController::class, 'edit'])
        ->middleware('permission:meme.edit')
        ->whereNumber('meme')
        ->name('memes.edit');

    Route::match(['PUT', 'PATCH'], '/memes/{meme}', [MemeController::class, 'update'])
        ->middleware('permission:meme.edit')
        ->whereNumber('meme')
        ->name('memes.update');

    Route::delete('/memes/{meme}', [MemeController::class, 'destroy'])
        ->middleware('permission:meme.delete')
        ->whereNumber('meme')
        ->name('memes.destroy');

    Route::post('/memes/{meme}/publish', [MemeController::class, 'publish'])
        ->middleware('permission:meme.publish')
        ->whereNumber('meme')
        ->name('memes.publish');
    Route::post('/memes/{meme}/reject', [MemeController::class, 'reject'])
        ->middleware('permission:meme.reject')
        ->whereNumber('meme')
        ->name('memes.reject');

    Route::post('/memes/{meme}/like', [MemeController::class, 'toggleLike'])
        ->middleware('permission:like.toggle')
        ->whereNumber('meme')
        ->name('memes.like');

    Route::post('/memes/{meme}/dislike', [MemeController::class, 'toggleDislike'])
        ->middleware('permission:like.toggle')
        ->whereNumber('meme')
        ->name('memes.dislike');

    Route::post('/memes/{meme}/comments', [CommentController::class, 'store'])
        ->middleware('permission:comment.create')
        ->whereNumber('meme');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/hide', [CommentController::class, 'hide'])->middleware('permission:comment.moderate');
    Route::post('/comments/{comment}/unhide', [CommentController::class, 'unhide'])->middleware('permission:comment.moderate');
});

Route::get('/memes/{meme}', [MemeController::class, 'show'])
    ->whereNumber('meme')
    ->name('memes.show');
