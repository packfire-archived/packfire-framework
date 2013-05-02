<?php
namespace Packfire\Debugger;

use \Packfire\FuelBlade\Container;

/**
 * Test class for Debugger.
 * Generated by PHPUnit on 2012-09-17 at 14:19:07.
 */
class DebuggerTest extends \PHPUnit_Framework_TestCase {

    const DEBUGOUTPUT = 'debugger.output';

    /**
     * @var \Packfire\Debugger\Debugger
     */
    protected $object;
    
    private $ioc;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers \Packfire\Debugger\Debugger::__construct
     * @covers \Packfire\Debugger\Debugger::__invoke
     */
    protected function setUp() {
        $this->ioc = new Container();
        $this->ioc[self::DEBUGOUTPUT] = $this->getMock('Packfire\\Debugger\\IOutput');

        $this->object = new Debugger();

        call_user_func($this->object, $this->ioc);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Debugger\Debugger::enabled
     */
    public function testEnabled() {
        $this->assertTrue($this->object->enabled());
        $this->assertTrue($this->object->enabled(true));
        $this->assertTrue($this->object->enabled());
        $this->assertFalse($this->object->enabled(false));
        $this->assertFalse($this->object->enabled());
    }

    /**
     * @covers \Packfire\Debugger\Debugger::dump
     */
    public function testDump() {
        $this->ioc[self::DEBUGOUTPUT]
                ->expects($this->once())
                ->method('write')
                ->with(
                        $this->equalTo('5'),
                        $this->isType('string'),
                        $this->equalTo('dump')
                    );
        $this->object->dump(5);
    }

    /**
     * @covers \Packfire\Debugger\Debugger::log
     */
    public function testLog() {
        $this->ioc[self::DEBUGOUTPUT]
                ->expects($this->once())
                ->method('write')
                ->with(
                        $this->equalTo(5),
                        $this->isNull(),
                        $this->equalTo('log')
                    );
        $this->object->log(5);
    }

    /**
     * @covers \Packfire\Debugger\Debugger::exception
     */
    public function testException() {
        $this->ioc[self::DEBUGOUTPUT]
                ->expects($this->once())
                ->method('write')
                ->with(
                        $this->equalTo('Error 5: test message'),
                        $this->isType('string'),
                        $this->equalTo('exception')
                    );
        $this->object->exception(new \Exception('test message', 5));
    }

    /**
     * @covers \Packfire\Debugger\Debugger::timeCheck
     */
    public function testTimeCheck() {
        $this->ioc[self::DEBUGOUTPUT]
                ->expects($this->once())
                ->method('write')
                ->with(
                        $this->isType('string'),
                        $this->isType('string'),
                        $this->equalTo('timeCheck')
                    );
        $this->object->timeCheck();
    }

    /**
     * @covers \Packfire\Debugger\Debugger::query
     */
    public function testQuery() {
        $this->ioc[self::DEBUGOUTPUT]
                ->expects($this->once())
                ->method('write')
                ->with(
                        $this->equalTo('SELECT * FROM `table`'),
                        $this->isType('string'),
                        $this->equalTo('query')
                    );
        $this->object->query('SELECT * FROM `table`');
    }

}
