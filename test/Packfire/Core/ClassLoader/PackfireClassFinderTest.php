<?php
namespace Packfire\Core\ClassLoader;

/**
 * Test class for PackfireClassFinder.
 * Generated by PHPUnit on 2012-10-14 at 04:19:48.
 */
class PackfireClassFinderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PackfireClassFinder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new PackfireClassFinder;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    protected function runClass($class){
        $reflector = new \ReflectionClass($class);
        $fn = $reflector->getFileName();
        $this->assertEquals($fn, $this->object->find($class));
    }

    /**
     * @covers Packfire\Core\ClassLoader\PackfireClassFinder::find
     */
    public function testFind() {
        $this->runClass('Packfire\\Packfire');
    }

    /**
     * @covers Packfire\Core\ClassLoader\PackfireClassFinder::find
     */
    public function testFind2() {
        $this->runClass('Packfire\\Core\\ClassLoader\\PackfireClassFinder');
    }

}