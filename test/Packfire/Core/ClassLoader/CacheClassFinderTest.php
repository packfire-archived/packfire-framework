<?php
namespace Packfire\Core\ClassLoader;

use Packfire\Cache\MockCache;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-24 at 06:30:56.
 */
class CacheClassFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Core\ClassLoader\CacheClassFinder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers Packfire\Core\ClassLoader\CacheClassFinder::__construct
     */
    protected function setUp()
    {
        $finder = new ClassFinder();
        $finder->addNamespace('Packfire', 'src');
        $this->object = new CacheClassFinder($finder);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Packfire\Core\ClassLoader\CacheClassFinder::find
     */
    public function testFind()
    {
        $ioc = array(
            'cache' => new MockCache()
        );
        call_user_func($this->object, $ioc);

        $this->assertEquals('src/Packfire/Packfire.php', $this->object->find('Packfire\\Packfire'));
        $this->assertTrue($ioc['cache']->check('Packfire\\Packfire'));
        $this->assertEquals('src/Packfire/Packfire.php', $ioc['cache']->get('Packfire\\Packfire'));
        $ioc['cache']->set('Packfire\\Packfire', 'Packfire.php');
        $this->assertEquals('Packfire.php', $this->object->find('\\Packfire\\Packfire'));
    }

    /**
     * @covers Packfire\Core\ClassLoader\CacheClassFinder::__invoke
     */
    public function test__invoke()
    {
        $ioc = array(
            'cache' => new \stdClass()
        );
        call_user_func($this->object, $ioc);

        $property = new \ReflectionProperty($this->object, 'cache');
        $property->setAccessible(true);
        $this->assertEquals($ioc['cache'], $property->getValue($this->object));
    }
}
