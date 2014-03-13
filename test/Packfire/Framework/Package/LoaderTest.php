<?php

namespace Packfire\Framework\Package;

use PHPUnit_Framework_TestCase;

class LoaderTest extends PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $cfg = new ConfigManager();

        $loader = new Loader($cfg);

        $this->assertEquals($cfg, $loader->config());
    }
}
