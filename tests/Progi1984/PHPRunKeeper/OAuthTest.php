<?php

use Progi1984\PHPRunKeeper\OAuth;

class OAuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var OAuth
     */
    protected $instance;
    
    protected function setUp()
    {
        $this->instance = new OAuth();
    }
    
    protected function tearDown()
    {
        $this->instance = null;
    }
    
    public function testConstruct()
    {
        $this->assertInstanceOf('Progi1984\PHPRunKeeper\OAuth', $this->instance);
    }
    
    public function testGetBaseAuthorizationUrl()
    {
        $this->assertEquals('https://runkeeper.com/apps/authorize', $this->instance->getBaseAuthorizationUrl());
    }
    public function testGetBaseAccessTokenUrl()
    {
        $this->assertEquals('https://runkeeper.com/apps/token', $this->instance->getBaseAccessTokenUrl(array()));
    }
    
    public function testGetResourceOwnerDetailsUrl()
    {
        $oMock = $this->getMock('League\OAuth2\Client\Token\AccessToken', array(), array(), '', false);
        $this->assertEquals('https://api.runkeeper.com/profile', $this->instance->getResourceOwnerDetailsUrl($oMock));
    }
}