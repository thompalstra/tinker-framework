<?php
use Hub\Base\View;
?>
<!DOCTYPE html>
<html>
    <head>
        <?=View::make('layouts/main/head')?>
    </head>
    <body>
        <?=View::make('layouts/main/nav')?>
        <?=View::make('layouts/main/sidebar')?>

        <?=$content?>

        <?=View::make('layouts/main/footer')?>
    </body>
</html>
