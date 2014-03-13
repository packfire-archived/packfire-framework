<?php

namespace Packfire\Framework\Package;

use PHPUnit_Framework_TestCase;
use Packfire\FuelBlade\Container;

class LoaderTest extends PHPUnit_Framework_TestCase
{

    public function testLoad()
    {
        $cfg = new ConfigManager();
        $container = new Container();
        $container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $cfg;

        $loader = new Loader($container);
        $loader->load(__DIR__ . '/../../../testPackages/package1');

        $this->assertTrue(isset($cfg['test']));
        $this->assertEquals('localhost', $cfg['test']->get('database', 'default', 'host'));
    }

    /**
     * @expectedException Packfire\Framework\Exceptions\ConfigLoadFailException
     */
    public function testLoadFail()
    {
        $cfg = new ConfigManager();
        $container = new Container();
        $container['Packfire\\Framework\\Package\\ConfigManagerInterface'] = $cfg;

        $loader = new Loader($container);
        $loader->load(__DIR__ . '/../../../testPackages/package2');
    }
}
