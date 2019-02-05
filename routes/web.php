<?php
use Hub\Base\Route;
Route::get("", "HomeController@index");
Route::get("twig", "HomeController@twig");
Route::get("smarty", "HomeController@smarty");
Route::get("info/services/{service}", "Info\ServicesController@service");

Route::get("queue/request", "QueueController@request");
