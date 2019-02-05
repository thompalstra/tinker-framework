<?php
return [
    "app" => [],
    "mysql" => [
        "host" => "localhost",
        "dbname" => "tinker",
        "user" => "root",
        "password" => ""
    ],
    "renderers" => [
        "Hub\Http\Renderer@output" => ["html", "php"],
        "Hub\Twig\Renderer@output" => ["twig.html"],
        "Hub\Smarty\Renderer@output" => ["tpl"]
    ]
];
