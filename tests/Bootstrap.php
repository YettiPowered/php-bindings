<?php

namespace Yetti\API\Tests;

/**
 * Test suite bootstrap.
 *
 * @author Sam Holman <sam@yetti.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 * @subpackage tests
 */

require_once 'API/Autoloader.php';
\Yetti\API\Autoloader::register();

require_once 'tests/Config.php';
require_once 'tests/AuthAbstract.php';
