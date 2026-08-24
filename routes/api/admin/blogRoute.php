<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\admin\BlogController;

Route::controller(BlogController::class)->group(function () {
    Route::any('/blogs', 'blogs');
    Route::any('/show-blogs', 'blogView');
    Route::any('/delete-blog', 'deleteBlog');
    Route::any('/trash-blog', 'trashBlog');
    Route::any('/recycle-blog', 'recycleBlog');
});