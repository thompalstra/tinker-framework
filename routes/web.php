<?php
use Hub\Base\Route;
Route::get("", "HomeController@index");
Route::get("twig", "HomeController@twig");
Route::get("smarty", "HomeController@smarty");
Route::get("info/services/{service}", "Info\ServicesController@service");

Route::get("queue/request", "QueueController@request");

Route::group(["prefix" => "docs"], function(){
    Route::get("", "DocsController@index");
    Route::group(["prefix" => "hub"], function(){
        Route::get("", "Docs/HubController@index");
        Route::get("base", "Docs/HubController@base");
        Route::get("cli", "Docs/HubController@cli");
        Route::get("db", "Docs/HubController@db");
        Route::get("http", "Docs/HubController@http");
        Route::get("queue", "Docs/HubController@queue");
        Route::get("smarty", "Docs/HubController@smarty");
        Route::get("twig", "Docs/HubController@twig");
    });
});
