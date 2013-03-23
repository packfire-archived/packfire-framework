<?php

namespace Packfire\Config\Driver;

require_once(__DIR__ . '/ConfigTestSetter.php');

/**
 * Test class for JsonConfig.
 * Generated by PHPUnit on 2012-10-28 at 06:37:32.
 */
class JsonConfigTest  extends ConfigTestSetter {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers \Packfire\Config\Driver\JsonConfig::read
     */
    protected function setUp() {
        $this->prepare('\\Packfire\\Config\\Driver\\JsonConfig');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

}