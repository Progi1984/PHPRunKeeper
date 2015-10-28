<?php
namespace Progi1984;

use \League\OAuth2\Client\Token\AccessToken as OauthAccessToken;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;

/**
 * 
 * @author Progi1984
 *
 * @method mixed getCommentThread(string $uri)
 * @method mixed getFitnessActivity(string $uri)
 * @method mixed getFitnessActivitySummary(string $uri)
 * @method mixed getMember(string $uri)
 * @method mixed getProfile()
 * @method mixed getSettings()
 * @method mixed getStrengthActivity(string $uri)
 * @method mixed getUser()
 * @method mixed getWeightSet(string $uri)
 * @method mixed getBackgroundActivitySetFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getDiabeteMeasurementSetFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getFitnessActivityFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getFriends(integer $numPage = null, integer $pageSize = null)
 * @method mixed getGeneralMeasurementSetFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getNutritionSetFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getRecords(integer $numPage = null, integer $pageSize = null)
 * @method mixed getSleepSetFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getStrengthActivityFeed(integer $numPage = null, integer $pageSize = null)
 * @method mixed getWeightSetFeed(integer $numPage = null, integer $pageSize = null)
 */
class PHPRunKeeper extends PHPRunKeeper\RunKeeperApi
{
    /**
     *
     * @var PHPRunKeeper\OAuth
     */
    protected $oAuth;

    /**
     *
     * @var OauthAccessToken
     */
    protected $oAccessToken;
    
    /**
     * 
     * @var string[][]
     */
    protected $callGetSimple = array(
        'getProfile' => array(
            'contentType' => self::CONTENT_TYPE_PROFILE,
            'uri' => self::URI_PROFILE,
        ),
        'getSettings' => array(
            'contentType' => self::CONTENT_TYPE_SETTINGS,
            'uri' => self::URI_SETTINGS,
        ),
        'getUser' => array(
            'contentType' => self::CONTENT_TYPE_USER,
            'uri' => self::URI_USER,
        )
    );
    
    /**
     *
     * @var string[][]
     */
    protected $callGetUri = array(
        'getCommentThread' => array(
            'contentType' => self::CONTENT_TYPE_COMMENT_THREAD,
        ),
        'getFitnessActivity' => array(
            'contentType' => self::CONTENT_TYPE_FITNESS_ACTIVITY,
        ),
        'getFitnessActivitySummary' => array(
            'contentType' => self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY,
        ),
        'getMember' => array(
            'contentType' => self::CONTENT_TYPE_MEMBER,
        ),
        'getStrengthActivity' => array(
            'contentType' => self::CONTENT_TYPE_STRENGTH_ACTIVITY,
        ),
        'getWeightSet' => array(
            'contentType' => self::CONTENT_TYPE_WEIGHT_SET,
        ),
    );

    /**
     * 
     * @var string[][]
     */
    protected $callGetComplex = array(
        'getBackgroundActivitySetFeed' => array(
            'contentType' => self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET_FEED,
            'uri' => self::URI_BACKGROUND_ACTIVITIES,
        ),
        'getDiabeteMeasurementSetFeed' => array(
            'contentType' => self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET_FEED,
            'uri' => self::URI_DIABETES,
        ),
        'getFitnessActivityFeed' => array(
            'contentType' => self::CONTENT_TYPE_FITNESS_ACTIVITY_FEED,
            'uri' => self::URI_FITNESS_ACTIVITIES,
        ),
        'getFriends' => array(
            'contentType' => self::CONTENT_TYPE_TEAM_FEED,
            'uri' => self::URI_TEAM,
        ),
        'getGeneralMeasurementSetFeed' => array(
            'contentType' => self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET_FEED,
            'uri' => self::URI_GENERAL_MEASUREMENTS,
        ),
        'getNutritionSetFeed' => array(
            'contentType' => self::CONTENT_TYPE_NUTRITION_SET_FEED,
            'uri' => self::URI_NUTRITION,
        ),
        'getRecords' => array(
            'contentType' => self::CONTENT_TYPE_RECORDS,
            'uri' => self::URI_RECORDS,
        ),
        'getSleepSetFeed' => array(
            'contentType' => self::CONTENT_TYPE_SLEEP_SET_FEED,
            'uri' => self::URI_SLEEP,
        ),
        'getStrengthActivityFeed' => array(
            'contentType' => self::CONTENT_TYPE_STRENGTH_ACTIVITY_FEED,
            'uri' => self::URI_STRENGTH_TRAINING_ACTIVITIES,
        ),
        'getWeightSetFeed' => array(
            'contentType' => self::CONTENT_TYPE_WEIGHT_SET_FEED,
            'uri' => self::URI_WEIGHT,
        ),
    );
    
    /**
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct($clientId, $clientSecret, $redirectUri)
    {
        $options = array(
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri
        );
        $this->oAuth = new PHPRunKeeper\OAuth($options);
        
        $this->oClient = new HttpClient(array(
            'base_uri' => $this->endPoint,
            'verify' => false
        ));
    }

    public function __call($name, array $arguments)
    {
        if (array_key_exists($name, $this->callGetSimple)) {
            return $this->requestGet($this->callGetSimple[$name]['contentType'], $this->callGetSimple[$name]['uri']);
        }
        if (array_key_exists($name, $this->callGetUri)) {
            return $this->requestGet($this->callGetUri[$name]['contentType'], $arguments[0]);
        }
        if (array_key_exists($name, $this->callGetComplex)) {
            return $this->requestGet($this->callGetComplex[$name]['contentType'], $this->callGetComplex[$name]['uri'], (isset($arguments[0]) ? $arguments[0] : null), (isset($arguments[1]) ? $arguments[1] : null));
        }
    }

    /**
     *
     * @return string
     */
    public function getAuthorizationUrl()
    {
        return $this->oAuth->getAuthorizationUrl();
    }

    /**
     *
     * @return string[]
     */
    protected function getHeaders()
    {
        return array(
            'Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()
        );
    }
    
    /**
     *
     * @return OauthAccessToken
     */
    public function getAccessToken($code = null)
    {
        if (! is_null($code)) {
            $this->oAccessToken = $this->oAuth->getAccessToken('authorization_code', [
                'code' => $code
            ]);
        }
        return $this->oAccessToken;
    }

    /**
     *
     * @param OauthAccessToken $token
     * @return PHPRunKeeper
     */
    public function setAccessToken(OauthAccessToken $token)
    {
        $this->oAccessToken = $token;
        return $this;
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/profile
     * @param array $arrayData
     * @return integer
     */
    public function setProfile(array $arrayData = array())
    {
        return $this->requestPut(self::CONTENT_TYPE_PROFILE, self::URI_PROFILE, $arrayData, $this->editProfile);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/settings
     * @param array $arrayData
     * @return integer
     */
    public function setSettings(array $arrayData = array())
    {
        return $this->requestPut(self::CONTENT_TYPE_SETTINGS, self::URI_SETTINGS, $arrayData, $this->editSettings);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setFitnessActivity($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_FITNESS_ACTIVITY, $uri, $arrayData, $this->editFitnessActivity);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setFitnessActivitySummary($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY, $uri, $arrayData, $this->editFitnessActSum );
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#newly-completed-activities
     * @param array $arrayData
     * @return string
     */
    public function addFitnessActivity(array $arrayData)
    {
        $arrayAdd = $this->editFitnessActivity;
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        $arrayAdd[] = 'detect_pauses';
        return $this->requestPost(self::CONTENT_TYPE_NEW_FITNESS_ACTIVITY, self::URI_FITNESS_ACTIVITIES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#deleting-activity
     * @link https://runkeeper.com/developer/healthgraph/strength-training#deleting-activity
     * @param string $uri
     * @return boolean
     */
    public function deleteActivity($uri)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $oResponse = $this->oClient->request('DELETE', $uri, array(
            'headers' => $arrayHeaders
        ));
        if ($oResponse->getStatusCode() == 204) {
            return true;
        }
        return false;
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/strength-training#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setStrengthActivity($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_STRENGTH_ACTIVITY, $uri, $arrayData, $this->editStrengthActivity);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/strength-training#newly-completed-activities
     * @param array $arrayData
     * @return string
     */
    public function addStrengthActivity(array $arrayData)
    {
        $arrayAdd = $this->editStrengthActivity;
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_STRENGTH_ACTIVITY, self::URI_STRENGTH_TRAINING_ACTIVITIES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setWeightSet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_WEIGHT_SET, $uri, $arrayData, $this->editWeight);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addWeightSet(array $arrayData)
    {
        $arrayAdd = $this->editWeight;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_WEIGHT_SET, self::URI_WEIGHT, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setBackgroundActivitySet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET, $uri, $arrayData, $this->editBkgActivity);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addBackgroundActivitySet(array $arrayData)
    {
        $arrayAdd = $this->editBkgActivity;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_BACKGROUND_ACTIVITY_SET, self::URI_BACKGROUND_ACTIVITIES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setSleepSet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_SLEEP_SET, $uri, $arrayData, $this->editSleep);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addSleepSet(array $arrayData)
    {
        $arrayAdd = $this->editSleep;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_SLEEP_SET, self::URI_SLEEP, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setNutritionSet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_NUTRITION_SET, $uri, $arrayData, $this->editNutrition);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addNutritionSet(array $arrayData)
    {
        $arrayAdd = $this->editNutrition;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_NUTRITION_SET, self::URI_NUTRITION, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setGeneralMeasurementSet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET, $uri, $arrayData, $this->editGenMeasurement);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addGeneralMeasurementSet(array $arrayData)
    {
        $arrayAdd = $this->editGenMeasurement;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_GENERAL_MEASUREMENT_SET, self::URI_GENERAL_MEASUREMENTS, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#past
     * @param string $uri
     * @param array $arrayData
     * @return integer
     */
    public function setDiabeteMeasurementSet($uri, array $arrayData)
    {
        return $this->requestPut(self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET, $uri, $arrayData, $this->editDiabeteMeasure);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addDiabeteMeasurementSet(array $arrayData)
    {
        $arrayAdd = $this->editDiabeteMeasure;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'white_cell_count';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_DIABETE_MEASUREMENT_SET, self::URI_DIABETES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/comments#thread
     * @param string $uri
     * @param string $comment
     * @return mixed
     */
    public function addComment($uri, $comment)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_COMMENT;
        $arrayHeaders['Accept'] = self::CONTENT_TYPE_COMMENT_THREAD;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'json' => array(
                'comment' => $comment
            )
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }
}
