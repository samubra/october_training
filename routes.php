<?php

use Samubra\Training\Models\Teacher;
use Samubra\Training\Models\Category;

Route::get('api/teachers',function(){
    return Category::all();
});