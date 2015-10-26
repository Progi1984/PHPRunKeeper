<?php
namespace Progi1984;

use \League\OAuth2\Client\Token\AccessToken as OauthAccessToken;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;

class PHPRunKeeper
{

    const CONTENT_TYPE_USER = 'application/vnd.com.runkeeper.User+json';

    const CONTENT_TYPE_PROFILE = 'application/vnd.com.runkeeper.Profile+json';

    const CONTENT_TYPE_SETTINGS = 'application/vnd.com.runkeeper.Settings+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_FEED = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.FitnessActivity+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY = 'application/vnd.com.runkeeper.FitnessActivitySummary+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY_FEED = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY = 'application/vnd.com.runkeeper.StrengthTrainingActivity+json';

    const CONTENT_TYPE_WEIGHT_SET_FEED = 'application/vnd.com.runkeeper.WeightSetFeed+json';

    const CONTENT_TYPE_WEIGHT_SET = 'application/vnd.com.runkeeper.WeightSet+json';

    const CONTENT_TYPE_NEW_WEIGHT_SET = 'application/vnd.com.runkeeper.NewWeightSet+json';

    const CONTENT_TYPE_RECORDS = 'application/vnd.com.runkeeper.Records+json';

    const CONTENT_TYPE_NEW_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.NewFitnessActivity+json';

    const CONTENT_TYPE_NEW_STRENGTH_ACTIVITY = 'application/vnd.com.runkeeper.NewStrengthTrainingActivity+json';

    const CONTENT_TYPE_BACKGROUND_ACTIVITY_SET_FEED = 'application/vnd.com.runkeeper.BackgroundActivitySetFeed+json';

    const CONTENT_TYPE_BACKGROUND_ACTIVITY_SET = 'application/vnd.com.runkeeper.BackgroundActivitySet+json';

    const CONTENT_TYPE_NEW_BACKGROUND_ACTIVITY_SET = 'application/vnd.com.runkeeper.NewBackgroundActivitySet+json';

    const CONTENT_TYPE_SLEEP_SET_FEED = 'application/vnd.com.runkeeper.SleepSetFeed+json';

    const CONTENT_TYPE_SLEEP_SET = 'application/vnd.com.runkeeper.SleepSet+json';

    const CONTENT_TYPE_NEW_SLEEP_SET = 'application/vnd.com.runkeeper.NewSleepSet+json';

    const CONTENT_TYPE_NUTRITION_SET_FEED = 'application/vnd.com.runkeeper.NutritionSetFeed+json';

    const CONTENT_TYPE_NUTRITION_SET = 'application/vnd.com.runkeeper.NutritionSet+json';

    const CONTENT_TYPE_NEW_NUTRITION_SET = 'application/vnd.com.runkeeper.NewNutritionSet+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_LIVE_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.LiveFitnessActivity+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_LIVE_FITNESS_ACTIVITY_UPDATE = 'application/vnd.com.runkeeper.LiveFitnessActivityUpdate+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_LIVE_FITNESS_ACTIVITY_COMPLETION = 'application/vnd.com.runkeeper.LiveFitnessActivityCompletion+json';

    const RETURN_SUCCESS = 1;

    const RETURN_ERROR_EDIT_BAD_FIELD = 2;

    const RETURN_ERROR_SAVE = 3;

    /**
     *
     * @var string
     */
    protected $endPoint = 'https://api.runkeeper.com/';

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
    protected $accessToken;

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
            $this->accessToken = $this->oAuth->getAccessToken('authorization_code', [
                'code' => $code
            ]);
        }
        return $this->accessToken;
    }

    /**
     *
     * @param OauthAccessToken $token            
     * @return PHPRunKeeper
     */
    public function setAccessToken(OauthAccessToken $token)
    {
        $this->accessToken = $token;
        return $this;
    }

    /**
     *
     * @return multitype:string
     */
    private function getHeaders()
    {
        return array(
            'Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()
        );
    }

    /**
     *
     * @param HttpResponse $oResponse            
     * @return mixed
     */
    private function treatResult(HttpResponse $oResponse)
    {
        $content = (string) $oResponse->getBody();
        $type = $oResponse->getHeader('Content-Type');
        $type = reset($type);
        $type = str_replace(';', '&', $type);
        parse_str($type, $arrType);
        if (isset($arrType['charset']) && $arrType['charset'] == 'ISO-8859-1') {
            $content = utf8_encode($content);
        }
        return json_decode($content, true);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/profile
     * @return mixed
     */
    public function getUser()
    {
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_USER;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', '/user', array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/profile
     * @return mixed
     */
    public function getProfile()
    {
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_PROFILE;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', '/profile', array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/profile
     * @param array $arrayData            
     * @return string|mixed
     */
    public function setProfile(array $arrayData = array())
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'athlete_type'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_PROFILE;
        $oResponse = $this->oClient->request('PUT', '/profile', array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/settings
     * @return mixed
     */
    public function getSettings()
    {
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_SETTINGS;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', '/settings', array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/settings
     * @param array $arrayData            
     * @return string|mixed
     */
    public function setSettings(array $arrayData = array())
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'share_fitness_activities',
                'share_map',
                'post_fitness_activity_facebook',
                'post_fitness_activity_twitter',
                'post_live_fitness_activity_facebook',
                'post_live_fitness_activity_twitter',
                'share_background_activities',
                'post_background_activity_facebook',
                'post_background_activity_twitter',
                'share_sleep',
                'post_sleep_facebook',
                'post_sleep_twitter',
                'share_nutrition',
                'post_nutrition_facebook',
                'post_nutrition_twitter',
                'share_weight',
                'post_weight_facebook',
                'post_weight_twitter',
                'share_general_measurements',
                'post_general_measurements_facebook',
                'post_general_measurements_twitter',
                'share_diabetes',
                'post_diabetes_facebook',
                'post_diabetes_twitter',
                'distance_units',
                'weight_units',
                'first_day_of_week'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_SETTINGS;
        $oResponse = $this->oClient->request('PUT', '/settings', array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#feed
     * @return mixed
     */
    public function getFitnessActivityFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_FITNESS_ACTIVITY_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/fitnessActivities';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @return mixed
     */
    public function getFitnessActivity($uri)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_FITNESS_ACTIVITY;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @return mixed
     */
    public function setFitnessActivity($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'type',
                'secondary_type',
                'equipment',
                'start_time',
                'total_distance',
                'duration',
                'average_heart_rate',
                'heart_rate',
                'total_calories',
                'notes',
                'path'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_FITNESS_ACTIVITY;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @return mixed
     */
    public function getFitnessActivitySummary($uri)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#past
     * @return mixed
     */
    public function setFitnessActivitySummary($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'type',
                'secondary_type',
                'equipment',
                'start_time',
                'total_distance',
                'duration',
                'average_heart_rate',
                'heart_rate',
                'total_calories',
                'notes'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/fitness-activities#newly-completed-activities
     * @param array $arrayData            
     * @return string
     */
    public function addFitnessActivity($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'type',
                'secondary_type',
                'equipment',
                'start_time',
                'total_distance',
                'duration',
                'average_heart_rate',
                'heart_rate',
                'total_calories',
                'notes',
                'path',
                'post_to_facebook',
                'post_to_twitter',
                'detect_pauses'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_FITNESS_ACTIVITY;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
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
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_STRENGTH_ACTIVITY_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/strengthTrainingActivities';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/strength-training#past
     * @return mixed
     */
    public function setStrengthActivity($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'start_time',
                'total_calories',
                'notes',
                'exercises'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_STRENGTH_ACTIVITY;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/strength-training#newly-completed-activities
     * @param array $arrayData            
     * @return string
     */
    public function addStrengthActivity($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'start_time',
                'notes',
                'total_calories',
                'exercises',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_STRENGTH_ACTIVITY;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/strength-training#past
     * @return mixed
     */
    public function getStrengthActivity($uri)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_STRENGTH_ACTIVITY;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#feed
     * @return mixed
     */
    public function getWeightSetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_WEIGHT_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/weight';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#past
     * @return mixed
     */
    public function getWeightSet($uri)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_WEIGHT_SET;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#past
     * @return mixed
     */
    public function setWeightSet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'weight',
                'free_mass',
                'fat_percent',
                'mass_weight',
                'bmi'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_WEIGHT_SET;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/weight-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addWeightSet($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'weight',
                'free_mass',
                'fat_percent',
                'mass_weight',
                'bmi',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_WEIGHT_SET;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#feed
     * @return mixed
     */
    public function getBackgroundActivitySetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/backgroundActivities';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#past
     * @return mixed
     */
    public function setBackgroundActivitySet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'calories_burned',
                'steps'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_BACKGROUND_ACTIVITY_SET;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/background-activity-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addBackgroundActivitySet($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'calories_burned',
                'steps',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_BACKGROUND_ACTIVITY_SET;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#feed
     * @return mixed
     */
    public function getSleepSetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_SLEEP_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/sleep';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#past
     * @return mixed
     */
    public function setSleepSet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'total_sleep',
                'deep',
                'rem',
                'light',
                'awake',
                'times_woken'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_SLEEP_SET;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/sleep-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addSleepSet($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'total_sleep',
                'deep',
                'rem',
                'light',
                'awake',
                'times_woken',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_SLEEP_SET;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#feed
     * @return mixed
     */
    public function getNutritionSetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NUTRITION_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/nutrition';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#past
     * @return mixed
     */
    public function setNutritionSet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'calories',
                'carbohydrates',
                'fat',
                'fiber',
                'protein',
                'sodium',
                'water',
                'meal'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NUTRITION_SET;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/nutrition-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addNutritionSet($arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'calories',
                'carbohydrates',
                'fat',
                'fiber',
                'protein',
                'sodium',
                'water',
                'meal',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_NUTRITION_SET;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        } else {
            return self::RETURN_ERROR_SAVE;
        }
    }

    /**
     *
     * @link https://runkeeper.com/developer/healthgraph/records
     * @return mixed
     */
    public function getRecords($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_RECORDS;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = '/records';
        if (! empty($numPage) || ! empty($pageSize)) {
            $url .= '?';
            if (! empty($numPage)) {
                $url .= 'page=' . $numPage;
            }
            if (! empty($numPage) && ! empty($pageSize)) {
                $url .= '&';
            }
            if (! empty($pageSize)) {
                $url .= 'pageSize=' . $pageSize;
            }
        }
        $oResponse = $this->oClient->request('GET', $url, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
    }
}
