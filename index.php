<?php
require_once 'vendor/autoload.php';
require_once 'Hub/autoload.php';

$application = new \Hub\Application();
$application->run(new \Hub\Http\Request($_SERVER));
