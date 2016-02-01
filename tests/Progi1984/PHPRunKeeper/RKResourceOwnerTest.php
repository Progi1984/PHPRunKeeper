<?php

use Progi1984\PHPRunKeeper\RKResourceOwner;

class RKResourceOwnerTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var RKResourceOwner
     */
    protected $instance;
    
    protected function setUp()
    {
        $this->instance = new RKResourceOwner();
    }
    
    protected function tearDown()
    {
        $this->instance = null;
    }
    
    public function testConstruct()
    {
        $this->assertInstanceOf('Progi1984\PHPRunKeeper\RKResourceOwner', $this->instance);
        $this->assertInternalType('array', $this->instance->toArray());
        $this->assertEmpty($this->instance->toArray());
    }
    
    public function testId()
    {
        $this->assertNull($this->instance->getId());
        
        $valueExpected = rand(1, 100);
        $this->instance = new RKResourceOwner(array('id' => $valueExpected));
        $this->assertEquals($valueExpected, $this->instance->getId());
    }
    
    public function testEmail()
    {
        $this->assertNull($this->instance->getEmail());
        
        $valueExpected = rand(1, 100);
        $this->instance = new RKResourceOwner(array('email' => $valueExpected));
        $this->assertEquals($valueExpected, $this->instance->getEmail());
    }
    
    public function testName()
    {
        $this->assertNull($this->instance->getName());
        
        $valueExpected = rand(1, 100);
        $this->instance = new RKResourceOwner(array('name' => $valueExpected));
        $this->assertEquals($valueExpected, $this->instance->getName());
    }
    
    public function testNickname()
    {
        $this->assertNull($this->instance->getNickname());
        
        $valueExpected = rand(1, 100);
        $this->instance = new RKResourceOwner(array('login' => $valueExpected));
        $this->assertEquals($valueExpected, $this->instance->getNickname());
    }
    
    public function testUrl()
    {
        $this->assertNull($this->instance->getUrl());
        
        $valueExpected = rand(1, 100);
        $this->instance = new RKResourceOwner(array('profile' => $valueExpected));
        $this->assertEquals($valueExpected, $this->instance->getUrl());
    }
}