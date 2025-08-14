<?php

/*
|--------------------------------------------------------------------------
| Test Bootstrap
|--------------------------------------------------------------------------
|
| This file is used to bootstrap the test environment before running tests.
| It ensures all necessary dependencies are loaded and the environment is
| properly configured for testing.
|
*/

// Load the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Set timezone to UTC for consistent testing
date_default_timezone_set('UTC');

// Ensure we're in testing environment
$_ENV['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');
