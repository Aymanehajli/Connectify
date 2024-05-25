<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\test;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SettingsController;
use App\Models\Block;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



//messages
Route::get('/chat/fetchMessages', [ChatController::class, 'fetchMessages'])->name('chat.fetchMessages');
Route::get('/chat/pollMessages', [ChatController::class, 'pollMessages'])->name('chat.pollMessages');
    



Route::get('/search-users', [ChatController::class, 'searchUsers']);
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');


Route::get('/friends/search', [FriendController::class, 'search'])->name('friends.search');


//friend request
Route::get('/friend-requests', [FriendRequestController::class, 'friendrequestlist'])->name('friend-requests');
Route::post('/friend-request/send/{id}', [FriendRequestController::class, 'send'])->name('friend-request.send');
Route::post('/friend-request/accept/{id}', [FriendRequestController::class, 'accept'])->name('friend-request.accept');
Route::post('/friend-request/refuse/{id}', [FriendRequestController::class, 'refuse'])->name('friend-request.refuse');
Route::get('/friend/check-friendship/{id}', [FriendRequestController::class, 'checkFriendship'])->name('check.friendship');


//Route::post('/add-friend/{id}', [FriendRequestController::class, 'addFriend'])->name('add.friend');
Route::post('/remove-request/{id}', [FriendRequestController::class, 'removeRequest'])->name('remove.request');


Route::get('/check-friend-request/{profileUserId}', [FriendRequestController::class, 'checkFriendRequest'])
    ->name('check.friend.request');

Route::post('/accept-friend/{profileUserId}', [FriendRequestController::class, 'acceptFriend'])
    ->name('accept.friend');

Route::post('/user/{id}/friend-request', [FriendRequestController::class, 'toggleFriendRequest'])->name('user.toggle-friend-request');
Route::post('/friend-request/send/{receiverId}', [FriendRequestController::class, 'sendRequest']);
Route::post('/friend-request/respond/{requestId}', [FriendRequestController::class, 'respondToRequest']);


Route::put('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

//Route::middleware('auth')->get('/notifications', [NotificationController::class, 'showNotifications'])->name('notifications.index');

// routes/web.php

Route::get('/notifications/fetch', [NotificationController::class, 'showNotifications'])->name('notifications.fetch');







Route::get('formul', [test::class, 'formul']);


Route::get('loginform', [LoginController::class, 'show'])->name('loginshow');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('HomePage', [LoginController::class, 'Home'])->name('homePage');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');


Route::resource('user',test::class);


Route::middleware(['auth', 'checkBlocked'])->group(function () {
    Route::get('/profile/{user}', [test::class, 'show'])->name('profile.show');
    // Other routes
});


//partie publicatioons
Route::resource('publication',PublicationController::class);
Route::get('/publications/{id}/likes', [PublicationController::class, 'likes'])->name('publications.likes');
Route::post('/publications/like/{id}', [PublicationController::class, 'like'])->name('publications.like');
Route::post('/publications/dislike/{id}', [PublicationController::class, 'dislike'])->name('publications.dislike');
Route::post('/publications/toggle-like/{id}', [PublicationController::class, 'toggleLike'])->name('publications.toggle-like');






Route::get('/getActiveUsers', [test::class, 'getActiveUsers'])->name('activeUser');


//settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.updateAccount');
Route::post('/settings/image', [SettingsController::class, 'updateProfileimage'])->name('settings.image');


Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');







//block user
Route::get('/blockedusers', [BlockController::class, 'index'])->name('block.index');
Route::post('/block/{id}', [BlockController::class, 'block'])->name('block');
Route::post('/unblock/{id}', [BlockController::class, 'unblock'])->name('unblock');
Route::get('/blocked', function () {
    return view('blocked');
})->name('blocked');