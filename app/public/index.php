<?php

/** @var \Fefas\BeRinha2023\App\Infrastructure\SymfonyKernel $kernel */
$kernel = require_once __DIR__ . "/../config/autoload.php";

$kernel->handleHttpCall();
