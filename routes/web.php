<?php
use Hub\Base\Route;

Route::group(["host" => "docs.*"], function(){
    Route::group(["prefix" => "docs", "name" => "docs."], function(){
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
});

Route::get("", "HomeController@index", ["name" => "home"]);
Route::get("contact", "HomeController@contact", ["name" => "contact"]);
Route::get("about", "HomeController@about", ["name" => "about"]);
Route::get("services", "HomeController@services", ["name" => "services"]);
Route::get("products", "HomeController@products", ["name" => "products"]);
