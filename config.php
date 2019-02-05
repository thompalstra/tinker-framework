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
        "Hub\Blade\Renderer@output" => ["blade.php"],
        "Hub\Twig\Renderer@output" => ["twig.html"],
        "Hub\Http\Renderer@output" => ["html", "php"]
    ]
];
