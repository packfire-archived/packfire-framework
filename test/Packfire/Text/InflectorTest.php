<?php
namespace Packfire\Text;

/**
 * Test class for Inflector.
 * Generated by PHPUnit on 2012-04-25 at 07:01:31.
 */
class InflectorTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var array
     */
    private $testCases;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->testCases = array(
            'hippos' => 'hippo',
            'donkeys' => 'donkey',
            'men' => 'man',
            'women' => 'woman',
            'keys' => 'key',
            'hackers' => 'hacker',
            'Dummies' => 'Dummy',
            'ALUMNI' => 'ALUMNUS',
            'pennies' => 'penny',
            'Fungi' => 'Fungus'
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers \Packfire\Text\Inflector::firstUpperCase
     */
    public function testFirstUpperCase(){
        $this->assertEquals(4, Inflector::firstUpperCase('testRun'));
        $this->assertEquals(6, Inflector::firstUpperCase('incaseTestRun'));
        $this->assertEquals(false, Inflector::firstUpperCase('home'));
        $this->assertEquals(0, Inflector::firstUpperCase('Home'));
    }

    /**
     * @covers \Packfire\Text\Inflector::isWordUpperCase
     */
    public function testIsWordUpperCase() {
        $this->assertTrue(Inflector::isWordUpperCase('LMAO'));
        $this->assertTrue(Inflector::isWordUpperCase('LOL!'));
        $this->assertTrue(Inflector::isWordUpperCase('MA0L1A:'));
        $this->assertFalse(Inflector::isWordUpperCase('MaYBe'));
        $this->assertFalse(Inflector::isWordUpperCase('Seriously!'));
        $this->assertFalse(Inflector::isWordUpperCase('real nuts'));
    }

    /**
     * @covers \Packfire\Text\Inflector::isWordLowerCase
     */
    public function testIsWordLowerCase() {
        $this->assertFalse(Inflector::isWordLowerCase('LMAO'));
        $this->assertFalse(Inflector::isWordLowerCase('LOL!'));
        $this->assertFalse(Inflector::isWordLowerCase('MA0L1A:'));
        $this->assertFalse(Inflector::isWordLowerCase('MaYBe'));
        $this->assertFalse(Inflector::isWordLowerCase('Seriously!'));
        $this->assertTrue(Inflector::isWordLowerCase('real nuts'));
        $this->assertTrue(Inflector::isWordLowerCase('sweet!'));
        $this->assertTrue(Inflector::isWordLowerCase('i am 40 years old.'));
    }

    /**
     * @covers \Packfire\Text\Inflector::isCapitalLetterWord
     */
    public function testIsCapitalLetterWord() {
        $this->assertTrue(Inflector::isCapitalLetterWord('Good'));
        $this->assertTrue(Inflector::isCapitalLetterWord('Great!'));
        $this->assertTrue(Inflector::isCapitalLetterWord('H4ppy n0w?'));
        $this->assertFalse(Inflector::isCapitalLetterWord('aGood'));
        $this->assertFalse(Inflector::isCapitalLetterWord('bGreat!'));
        $this->assertFalse(Inflector::isCapitalLetterWord('cH4ppy n0w?'));
    }

    /**
     * @covers \Packfire\Text\Inflector::singular
     */
    public function testSingular() {
        $this->testCases = array_merge($this->testCases, array(
            'man' => 'man',
            'key' => 'key',
            'mummy' => 'mummy',
            'yatch' => 'yatch'
        ));
        foreach($this->testCases as $plural => $singular){
            $this->assertEquals($singular, Inflector::singular($plural));
        }
    }

    /**
     * @covers \Packfire\Text\Inflector::plural
     */
    public function testPlural() {
        $this->testCases = array_merge($this->testCases, array(
            'men' => 'men',
            'keys' => 'keys',
            'mummies' => 'mummies',
            'yatches' => 'yatches'
        ));
        foreach($this->testCases as $plural => $singular){
            $this->assertEquals($plural, Inflector::plural($singular));
        }
    }

    /**
     * @covers \Packfire\Text\Inflector::quantify
     */
    public function testQuantify() {
        $quantifier = 1;
        foreach($this->testCases as $plural => $singular){
            $this->assertEquals($singular, Inflector::quantify($quantifier, $singular));
        }
        $quantifier = 5;
        foreach($this->testCases as $plural => $singular){
            $this->assertEquals($plural, Inflector::quantify($quantifier, $singular));
        }
        $quantifier = 100;
        foreach($this->testCases as $plural => $singular){
            $this->assertEquals($plural, Inflector::quantify($quantifier, $singular));
            $this->assertEquals($singular, Inflector::quantify($quantifier, $singular, $singular));
        }
        $this->assertEquals('aaa', Inflector::quantify(2, 'bbb', 'aaa'));
        $this->assertEquals('bbb', Inflector::quantify(1, 'bbb', 'aaa'));
    }

}