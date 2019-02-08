<?php
use Hub\Widgets\Nav;
use Hub\Base\Route;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Tinker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <?=Nav::widget([
        "items" => [
            [
                "label" => "Home",
                "link" => Route::to("home"),
                "options" => []
            ],
            [
                "label" => "Contact",
                "link" => Route::to("contact"),
                "options" => []
            ],
            [
                "label" => "About",
                "link" => Route::to("about"),
                "options" => []
            ],
            [
                "label" => "Services",
                "link" => Route::to("services"),
                "options" => []
            ],
            [
                "label" => "Products",
                "link" => Route::to("products"),
                "options" => []
            ],
            [
                "label" => "dropdown",
                "link" => "#",
                "items" => [
                    [
                        "label" => "Contact",
                        "link" => Route::to("contact"),
                        "options" => []
                    ],
                    [
                        "label" => "About",
                        "link" => Route::to("about"),
                        "options" => []
                    ]
                ]
            ]
        ]
    ])?>
</nav>
