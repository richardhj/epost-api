<?php

/**
 * This file is part of richardhj/epost-api.
 *
 * Copyright (c) 2015-2017 Richard Henkenjohann
 *
 * @package   richardhj/epost-api
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2015-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/epost-api/blob/master/LICENSE LGPL-3.0
 */

error_reporting(E_ALL);

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    echo 'Could not find "vendor/autoload.php". Did you forget to run "composer install --dev"?'.PHP_EOL;
    exit(1);
}

require_once $autoloadFile;
