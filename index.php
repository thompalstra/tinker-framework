<?php
require 'vendor/autoload.php';

$application = new \Hub\Application();
$application->run(new \Hub\Http\Request($_SERVER));
