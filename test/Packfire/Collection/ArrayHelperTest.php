<?php
namespace Packfire\Collection;

/**
 * Test class for ArrayHelper.
 * Generated by PHPUnit on 2012-06-29 at 01:52:04.
 */
class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Collection\ArrayHelper::mergeRecursiveDistinct
     */
    public function testMergeRecursiveDistinct()
    {
        $alpha = array(
            'app' => array(
                'apple' => 'one',
                'charlie' => 'three'
            ),
            'database' => array(
                'host' => 'localhost'
            ),
            'route' => false,
            'session' => array(
                'name' => 'packfire',
                'enabled' => true
            )
        );
        $bravo = array(
            'app' => array(
                'apple' => 'one',
                'beta' => 'two',
                'charlie' => 'three'
            ),
            'database.local' => array(
                'host' => '192.168.1.1'
            ),
            'route' => array(
                'disabled' => false
            ),
            'session' => array(
                'enabled' => false
            )
        );
        $delta = ArrayHelper::mergeRecursiveDistinct($alpha, $bravo, array());

        $charlie = array(
            'app' => array(
                'apple' => 'one',
                'beta' => 'two',
                'charlie' => 'three'
            ),
            'database' => array(
                'host' => 'localhost'
            ),
            'database.local' => array(
                'host' => '192.168.1.1'
            ),
            'route' => array(
                'disabled' => false
            ),
            'session' => array(
                'name' => 'packfire',
                'enabled' => false
            )
        );
        $this->assertEquals($charlie, $delta);

    }

}
