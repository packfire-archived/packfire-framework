<?php

pload('packfire.io.file.pPath');
pload('packfire.io.file.pFileSystem');

/**
 * Test class for pPath.
 * Generated by PHPUnit on 2012-07-16 at 05:15:13.
 */
class pPathTest extends PHPUnit_Framework_TestCase {

    private $dir;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->dir= pPath::fileName(__FILE__) . DIRECTORY_SEPARATOR . 'test';
        if(is_dir($this->dir)){
            rmdir($this->dir);
        }
        if(is_dir(dirname($this->dir))){
            rmdir(dirname($this->dir));
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        if(is_dir($this->dir)){
            rmdir($this->dir);
        }
        if(is_dir(dirname($this->dir))){
            rmdir(dirname($this->dir));
        }
    }

    /**
     * @covers pPath::create
     */
    public function testCreate() {
        $path = new pPath($this->dir);
        $this->assertFalse(pFileSystem::pathExists($this->dir));
        $path->create();
        $this->assertTrue(pFileSystem::pathExists($this->dir));
    }
    
    /**
     * @covers pPath::exists
     */
    public function testExists(){
        $path = new pPath($this->dir);
        $this->assertFalse($path->exists());
        $path->create();
        $this->assertTrue($path->exists());
        $path->delete();
        $this->assertFalse($path->exists());
    }

    /**
     * @covers pPath::permission
     */
    public function testPermission() {
        $path = new pPath($this->dir);
        $path->create();
        $permission = $path->permission();
        $this->assertEquals(substr(decoct(fileperms($this->dir)), 2), $permission);
    }

    /**
     * @covers pPath::delete
     */
    public function testDelete() {
        $path = new pPath($this->dir);
        $path->create();
        $this->assertTrue(pFileSystem::pathExists($this->dir));
        $path->delete();
        $this->assertFalse(pFileSystem::pathExists($this->dir));
    }

    /**
     * @covers pPath::copy
     */
    public function testCopy() {
        $copy = pPath::fileName(__FILE__) . '2';
        @rmdir($copy . DIRECTORY_SEPARATOR . 'test');
        @rmdir($copy);
        $original = new pPath($this->dir);
        $original->create();
        pPath::copy(dirname($this->dir), $copy);
        $this->assertTrue(is_dir($copy));
        $this->assertTrue(is_dir($copy . DIRECTORY_SEPARATOR . 'test'));
        @rmdir($copy . DIRECTORY_SEPARATOR . 'test');
        @rmdir($copy);
    }

    /**
     * @covers pPath::clear
     */
    public function testClear() {
        $original = new pPath($this->dir);
        $original->create();
        $path = new pPath(dirname($this->dir));
        $path->clear();
        $this->assertFalse(is_dir($this->dir));
        $this->assertTrue(is_dir(dirname($this->dir)));
    }

    /**
     * @covers pPath::combine
     */
    public function testCombine1() {
        $path = DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'call' . DIRECTORY_SEPARATOR . 'me' . DIRECTORY_SEPARATOR . 'maybe';
        $this->assertEquals($path, pPath::combine('/var/test', 'call/me/maybe'));
        $this->assertEquals($path, pPath::combine('/var/test/bye', '../call/me/maybe'));
    }
    
    /**
     * @covers pPath::combine
     */
    public function testCombine2(){
        $path = 'C:' . DIRECTORY_SEPARATOR . 'workspace' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . 'packfire';
        $this->assertEquals($path, pPath::combine('C:\\workspace', 'projects\\packfire'));
        $this->assertEquals($path, pPath::combine('C:\\workspace\\taco', '../projects/packfire'));
    }

    /**
     * @covers pPath::tempPath
     */
    public function testTempPath() {
        $this->assertNotEmpty(pPath::tempPath());
        $this->assertEquals(sys_get_temp_dir(), pPath::tempPath());
    }

    /**
     * @covers pPath::fileName
     */
    public function testFileName() {
        $this->assertEquals('test', pPath::fileName('C:\\michael\\jackson\\this_is_it\\test.bak'));
        $this->assertEquals('test', pPath::fileName('/opt/ion/is/not/yours/test.bin'));
        $this->assertEquals('test', pPath::fileName('jordan\\test.bak'));
        $this->assertEquals('test', pPath::fileName('runner/test.bin'));
    }

    /**
     * @covers pPath::baseName
     */
    public function testBaseName() {
        $this->assertEquals('test.bak', pPath::baseName('C:\\michael\\jackson\\this_is_it\\test.bak'));
        $this->assertEquals('test.bin', pPath::baseName('/opt/ion/is/not/yours/test.bin'));
        $this->assertEquals('test.bak', pPath::baseName('jordan\\test.bak'));
        $this->assertEquals('test.bin', pPath::baseName('runner/test.bin'));
    }

    /**
     * @covers pPath::extension
     */
    public function testExtension() {
        $this->assertEquals('bak', pPath::extension('C:\\michael\\jackson\\this_is_it\\test.bak'));
        $this->assertEquals('bin', pPath::extension('/opt/ion/is/not/yours/test.bin'));
        $this->assertEquals('bak', pPath::extension('jordan\\test.bak'));
        $this->assertEquals('bin', pPath::extension('runner/test.bin'));
    }

    /**
     * @covers pPath::path
     */
    public function testPath() {
        $this->assertEquals('C:' . DIRECTORY_SEPARATOR . 'michael' . DIRECTORY_SEPARATOR . 'jackson' . DIRECTORY_SEPARATOR . 'this_is_it',
                pPath::path('C:\\michael\\jackson\\this_is_it\\test.bak'));
        $this->assertEquals('' . DIRECTORY_SEPARATOR . 'opt' . DIRECTORY_SEPARATOR . 'ion' . DIRECTORY_SEPARATOR . 'is' . DIRECTORY_SEPARATOR . 'not' . DIRECTORY_SEPARATOR . 'yours',
                pPath::path('/opt/ion/is/not/yours/test.bin'));
        $this->assertEquals('jordan', pPath::path('jordan\\test.bak'));
        $this->assertEquals('runner', pPath::path('runner/test.bin'));
    }

    /**
     * @covers pPath::pathInfo
     */
    public function testPathInfo() {
        $this->assertCount(4, pPath::pathInfo('/opt/ion/is/not/yours/test.bin'));
        $this->assertEquals('bin', pPath::pathInfo('/opt/ion/is/not/yours/test.bin', pPathPart::EXTENSION));
    }

    /**
     * @covers pPath::currentWorkingPath
     */
    public function testCurrentWorkingPath() {
        $this->assertNotEmpty(pPath::currentWorkingPath());
        $this->assertEquals(getcwd(), pPath::currentWorkingPath());
    }

    /**
     * @covers pPath::scriptPath
     */
    public function testScriptPath() {
        $this->assertNotEmpty(pPath::scriptPath());
        $this->assertEquals(dirname($_SERVER['SCRIPT_NAME']), pPath::scriptPath());
    }
    
    /**
     * @covers pPath::classPathName
     */
    public function testClassPathName() {
        $this->assertEquals(__FILE__, pPath::classPathName('pPathTest'));
        $this->assertEquals(pPath::combine(__PACKFIRE_ROOT__, 'Packfire.php'), pPath::classPathName('Packfire'));
    }

}