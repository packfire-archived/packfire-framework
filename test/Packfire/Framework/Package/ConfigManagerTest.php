<?php

namespace Packfire\Framework\Package;

use Packfire\Config\Config;
use PHPUnit_Framework_TestCase;

class ConfigManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCommitGet()
    {
        $cfg = new ConfigManager();

        $config1 = new Config();
        $config1->set('sec1', 'key1', '10');

        $cfg->commit('first', $config1);
        $this->assertEquals($config1, $cfg->get('first'));
        $this->assertEquals($config1, $cfg['first']);
        $this->assertEquals('10', $cfg->get('first')->get('sec1', 'key1'));

        $config2 = new Config();
        $config2->set('sec2', 'key1', '3');

        $cfg['first'] = $config2;
        $this->assertEquals('3', $cfg->get('first')->get('sec2', 'key1'));
        $this->assertEquals('10', $cfg->get('first')->get('sec1', 'key1'));
    }

    /**
     * @expectedException Packfire\Framework\Exceptions\ConfigNotFoundException
     */
    public function testGetNotFound()
    {
        $cfg = new ConfigManager();
        $cfg->get('died');
    }

    public function testRemove()
    {
        $cfg = new ConfigManager();

        $config1 = new Config();
        $config1->set('sec1', 'key1', '10');
        $cfg->commit('first', $config1);

        $config2 = new Config();
        $config2->set('sec2', 'key1', '3');
        $cfg['second'] = $config2;

        $cfg->remove('first');
        $this->assertTrue(isset($cfg['second']));
        $this->assertFalse(isset($cfg['first']));

        unset($cfg['second']);
        $this->assertFalse(isset($cfg['second']));
        $this->assertFalse(isset($cfg['first']));
    }
}
