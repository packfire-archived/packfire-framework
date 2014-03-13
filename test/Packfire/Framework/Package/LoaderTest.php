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

    public function testLoad()
    {
        $cfg = new ConfigManager();

        $loader = new Loader($cfg);
        $loader->load(__DIR__ . '/../../../testPackages/package1');

        $this->assertEquals($cfg, $loader->config());
        $this->assertTrue(isset($cfg['test']));
        $this->assertEquals('localhost', $cfg['test']->get('database', 'default', 'host'));
    }

    /**
     * @expectedException Packfire\Framework\Exceptions\ConfigLoadFailException
     */
    public function testLoadFail()
    {
        $cfg = new ConfigManager();

        $loader = new Loader($cfg);
        $loader->load(__DIR__ . '/../../../testPackages/package2');
    }
}
