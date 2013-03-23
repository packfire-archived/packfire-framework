<?php
namespace Packfire\Config\Driver;

abstract class ConfigTestSetter extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Packfire\Config\Driver\IniConfig
     */
    protected $object;
    
    protected $file = 'test/Files/sampleConfig';
    
    public function prepare($class){
        $this->object = $this->getMock($class, array('read'), array($this->file));
        
        $property = new \ReflectionProperty($this->object, 'data');
        $property->setAccessible(true);
        $data = array(
            'first_section' => array(
                'one' => 1,
                'five' => 5,
                'animal' => 'BIRD'
            ),
            'second_section' => array(
                'path' => '/usr/local/bin',
                'URL' => 'http://www.example.com/~username'
            ),
            'third_section' => array(
                'phpversion' => array(
                    '5.0',
                    '5.1',
                    '5.2',
                    '5.3'
                )
            )
        );
        $property->setValue($this->object, $data);
    }
    
}
