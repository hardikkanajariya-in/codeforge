<?php

namespace PHPSTORM_META {

    // This helps IDEs understand that TestCase extends PHPUnit's TestCase
    override(\PHPUnit\Framework\TestCase::class, map([
        '' => '@',
    ]));

    // This helps IDEs understand Orchestra Testbench
    override(\Orchestra\Testbench\TestCase::class, map([
        '' => '@',
    ]));
}