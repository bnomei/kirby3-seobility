<?php

require_once __DIR__.'/../vendor/autoload.php';

Kirby::$enableWhoops = false;
$kirby = new Kirby;

echo $kirby->render();
