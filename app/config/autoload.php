<?php

use Fefas\BeRinha2023\App\Infrastructure\SymfonyKernel;

require_once "{$_ENV['COMPOSER_VENDOR_DIR']}/autoload.php";

return SymfonyKernel::booted();