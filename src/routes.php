<?php

Route::get('/image/{folder}/{type}', 'Waxis\Core\ImageController@get');
Route::get('/image/{folder}/{type}/{file}', 'Waxis\Core\ImageController@get');