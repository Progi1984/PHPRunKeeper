<?php
use Progi1984\PHPRunKeeper;
use League\OAuth2\Client\Token\AccessToken;

class PHPRunKeeperTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var PHPRunKeeper
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new PHPRunKeeper(PHPRUNKEEPER_API_ID, PHPRUNKEEPER_API_SECRET, PHPRUNKEEPER_API_URI);
    }

    protected function tearDown()
    {
        $this->instance = null;
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Progi1984\PHPRunKeeper', $this->instance);
    }

    public function testGetAuthorizationUrl()
    {
        $this->assertStringStartsWith('https://runkeeper.com/apps/authorize', $this->instance->getAuthorizationUrl());
    }
    
    public function testAccessToken()
    {
        $this->assertNull($this->instance->getAccessToken());
        
        $oAccessToken = new AccessToken(array('access_token' => PHPRUNKEEPER_API_ACCESSTOKEN));
        $this->assertInstanceOf('Progi1984\PHPRunKeeper', $this->instance->setAccessToken($oAccessToken));
        $this->assertInstanceOf('League\OAuth2\Client\Token\AccessToken', $this->instance->getAccessToken());
    }
    
    public function testApiGet()
    {
        $oAccessToken = new AccessToken(array('access_token' => PHPRUNKEEPER_API_ACCESSTOKEN));
        $this->instance->setAccessToken($oAccessToken);
        
        $oResult = $this->instance->getSettings();
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
    }
    
    public function testApiGetActivity()
    {
        $oAccessToken = new AccessToken(array('access_token' => PHPRUNKEEPER_API_ACCESSTOKEN));
        $this->instance->setAccessToken($oAccessToken);
        
        $oResult = $this->instance->getFitnessActivityFeed();
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
        
        $oResult = $this->instance->getFitnessActivity($oResult['items'][0]['uri']);
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
    }
    
    public function testApiComment()
    {
        $oAccessToken = new AccessToken(array('access_token' => PHPRUNKEEPER_API_ACCESSTOKEN));
        $this->instance->setAccessToken($oAccessToken);
        
        $oResult = $this->instance->getFitnessActivityFeed();
        $oResult = $this->instance->getFitnessActivity($oResult['items'][0]['uri']);
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
        
        $arrayComments = $this->instance->getCommentThread($oResult['comments']);
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
        
        $countComments = count($arrayComments['comments']);
        
        $return = $this->instance->addComment($oResult['comments'], 'Ceci est un test. AAAA éééé \'\'\'\' """"" ùùùù %%%% @@@@');
        
        $arrayComments = $this->instance->getCommentThread($oResult['comments']);
        
        $this->assertInternalType('array', $oResult);
        $this->assertNotEmpty($oResult);
        $this->assertCount($countComments + 1, $arrayComments['comments']);
        
    }
}
