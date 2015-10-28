<?php
namespace Progi1984;

use \League\OAuth2\Client\Token\AccessToken as OauthAccessToken;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;

class PHPRunKeeper extends PHPRunKeeper\RunKeeperApi
{
    /**
     *
     * @var PHPRunKeeper\OAuth
     */
    protected $oAuth;

    /**
     *
     * @var HttpClient
     */
    protected $oClient;

    /**
     *
     * @var OauthAccessToken
     */
    protected $oAccessToken;

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
     * @return mixed
     */
    public function getUser()
    {
        return $this->requestGet(self::CONTENT_TYPE_USER, self::URI_USER);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/profile
     * @return mixed
     */
    public function getProfile()
    {
        return $this->requestGet(self::CONTENT_TYPE_PROFILE, self::URI_PROFILE);
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
     * @return mixed
     */
    public function getSettings()
    {
        return $this->requestGet(self::CONTENT_TYPE_SETTINGS, self::URI_SETTINGS);
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
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#feed
     * @return mixed
     */
    public function getFitnessActivityFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_FITNESS_ACTIVITY_FEED, self::URI_FITNESS_ACTIVITIES, $numPage, $pageSize);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @return mixed
     */
    public function getFitnessActivity($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_FITNESS_ACTIVITY, $uri);
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
     * @return mixed
     */
    public function getFitnessActivitySummary($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY, $uri);
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
        return $this->requestPut(self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY, $uri, $arrayData, $this->editFitnessActivitySummary);
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
     * @link https://runkeeper.com/developer/healthgraph/strength-training#strength-training-activity-feed
     * @return mixed
     */
    public function getStrengthActivityFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_STRENGTH_ACTIVITY_FEED, self::URI_STRENGTH_TRAINING_ACTIVITIES, $numPage, $pageSize);
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
     * @link https://runkeeper.com/developer/healthgraph/strength-training#past
     * @return mixed
     */
    public function getStrengthActivity($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_STRENGTH_ACTIVITY, $uri);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#feed
     * @return mixed
     */
    public function getWeightSetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_WEIGHT_SET_FEED, self::URI_WEIGHT, $numPage, $pageSize);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#past
     * @return mixed
     */
    public function getWeightSet($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_WEIGHT_SET, $uri);
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
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#feed
     * @return mixed
     */
    public function getBackgroundActivitySetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET_FEED, self::URI_BACKGROUND_ACTIVITIES, $numPage, $pageSize);
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
        return $this->requestPut(self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET, $uri, $arrayData, $this->editBackgroundActivity);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addBackgroundActivitySet(array $arrayData)
    {
        $arrayAdd = $this->editBackgroundActivity;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_BACKGROUND_ACTIVITY_SET, self::URI_BACKGROUND_ACTIVITIES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#feed
     * @return mixed
     */
    public function getSleepSetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_SLEEP_SET_FEED, self::URI_SLEEP, $numPage, $pageSize);
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
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#feed
     * @return mixed
     */
    public function getNutritionSetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_NUTRITION_SET_FEED, self::URI_NUTRITION, $numPage, $pageSize);
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
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#feed
     * @return mixed
     */
    public function getGeneralMeasurementSetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET_FEED, self::URI_GENERAL_MEASUREMENTS, $numPage, $pageSize);
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
        return $this->requestPut(self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET, $uri, $arrayData, $this->editGeneralMeasurement);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addGeneralMeasurementSet(array $arrayData)
    {
        $arrayAdd = $this->editGeneralMeasurement;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_GENERAL_MEASUREMENT_SET, self::URI_GENERAL_MEASUREMENTS, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#feed
     * @return mixed
     */
    public function getDiabeteMeasurementSetFeed($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET_FEED, self::URI_DIABETES, $numPage, $pageSize);
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
        return $this->requestPut(self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET, $uri, $arrayData, $this->editDiabeteMeasurement);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#new
     * @param array $arrayData
     * @return string
     */
    public function addDiabeteMeasurementSet(array $arrayData)
    {
        $arrayAdd = $this->editDiabeteMeasurement;
        $arrayAdd[] = 'timestamp';
        $arrayAdd[] = 'white_cell_count';
        $arrayAdd[] = 'post_to_facebook';
        $arrayAdd[] = 'post_to_twitter';
        return $this->requestPost(self::CONTENT_TYPE_NEW_DIABETE_MEASUREMENT_SET, self::URI_DIABETES, $arrayData, $arrayAdd);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/records
     * @return mixed
     */
    public function getRecords($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_RECORDS, self::URI_RECORDS, $numPage, $pageSize);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/friends#feed
     * @return mixed
     */
    public function getFriends($numPage = null, $pageSize = null)
    {
        return $this->requestGet(self::CONTENT_TYPE_TEAM_FEED, self::URI_TEAM, $numPage, $pageSize);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/friends#members
     * @return mixed
     */
    public function getMember($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_MEMBER, $uri);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/comments#thread
     * @return mixed
     */
    public function getCommentThread($uri)
    {
        return $this->requestGet(self::CONTENT_TYPE_COMMENT_THREAD, $uri);
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
