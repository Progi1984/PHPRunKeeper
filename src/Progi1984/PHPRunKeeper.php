<?php
namespace Progi1984;

use \League\OAuth2\Client\Token\AccessToken as OauthAccessToken;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;

class PHPRunKeeper
{

    const CONTENT_TYPE_BACKGROUND_ACTIVITY_SET = 'application/vnd.com.runkeeper.BackgroundActivitySet+json';

    const CONTENT_TYPE_BACKGROUND_ACTIVITY_SET_FEED = 'application/vnd.com.runkeeper.BackgroundActivitySetFeed+json';

    const CONTENT_TYPE_COMMENT = 'application/vnd.com.runkeeper.Comment+json';

    const CONTENT_TYPE_COMMENT_THREAD = 'application/vnd.com.runkeeper.CommentThread+json';

    const CONTENT_TYPE_DIABETE_MEASUREMENT_SET = 'application/vnd.com.runkeeper.DiabetesMeasurementSet+json';

    const CONTENT_TYPE_DIABETE_MEASUREMENT_SET_FEED = 'application/vnd.com.runkeeper.DiabetesMeasurementSetFeed+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.FitnessActivity+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_FEED = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY = 'application/vnd.com.runkeeper.FitnessActivitySummary+json';

    const CONTENT_TYPE_GENERAL_MEASUREMENT_SET = 'application/vnd.com.runkeeper.GeneralMeasurementSet+json';

    const CONTENT_TYPE_GENERAL_MEASUREMENT_SET_FEED = 'application/vnd.com.runkeeper.GeneralMeasurementSetFeed+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_INVITATION = 'application/vnd.com.runkeeper.Invitation+json';

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
    const CONTENT_TYPE_LIVE_FITNESS_ACTIVITY_COMPLETION = 'application/vnd.com.runkeeper.LiveFitnessActivityCompletion+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_LIVE_FITNESS_ACTIVITY_UPDATE = 'application/vnd.com.runkeeper.LiveFitnessActivityUpdate+json';

    const CONTENT_TYPE_MEMBER = 'application/vnd.com.runkeeper.Member+json';

    const CONTENT_TYPE_NEW_BACKGROUND_ACTIVITY_SET = 'application/vnd.com.runkeeper.NewBackgroundActivitySet+json';

    const CONTENT_TYPE_NEW_DIABETE_MEASUREMENT_SET = 'application/vnd.com.runkeeper.NewDiabetesMeasurementSet+json';

    const CONTENT_TYPE_NEW_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.NewFitnessActivity+json';

    const CONTENT_TYPE_NEW_GENERAL_MEASUREMENT_SET = 'application/vnd.com.runkeeper.NewGeneralMeasurementSet+json';

    const CONTENT_TYPE_NEW_NUTRITION_SET = 'application/vnd.com.runkeeper.NewNutritionSet+json';

    const CONTENT_TYPE_NEW_SLEEP_SET = 'application/vnd.com.runkeeper.NewSleepSet+json';

    const CONTENT_TYPE_NEW_STRENGTH_ACTIVITY = 'application/vnd.com.runkeeper.NewStrengthTrainingActivity+json';

    const CONTENT_TYPE_NEW_WEIGHT_SET = 'application/vnd.com.runkeeper.NewWeightSet+json';

    const CONTENT_TYPE_NUTRITION_SET = 'application/vnd.com.runkeeper.NutritionSet+json';

    const CONTENT_TYPE_NUTRITION_SET_FEED = 'application/vnd.com.runkeeper.NutritionSetFeed+json';

    const CONTENT_TYPE_PROFILE = 'application/vnd.com.runkeeper.Profile+json';

    const CONTENT_TYPE_RECORDS = 'application/vnd.com.runkeeper.Records+json';

    /**
     * Not implemented
     *
     * @var string
     */
    const CONTENT_TYPE_REPLY = 'application/vnd.com.runkeeper.Reply+json';

    const CONTENT_TYPE_SETTINGS = 'application/vnd.com.runkeeper.Settings+json';

    const CONTENT_TYPE_SLEEP_SET = 'application/vnd.com.runkeeper.SleepSet+json';

    const CONTENT_TYPE_SLEEP_SET_FEED = 'application/vnd.com.runkeeper.SleepSetFeed+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY = 'application/vnd.com.runkeeper.StrengthTrainingActivity+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY_FEED = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

    const CONTENT_TYPE_TEAM_FEED = 'application/vnd.com.runkeeper.TeamFeed+json';

    const CONTENT_TYPE_USER = 'application/vnd.com.runkeeper.User+json';

    const CONTENT_TYPE_WEIGHT_SET = 'application/vnd.com.runkeeper.WeightSet+json';

    const CONTENT_TYPE_WEIGHT_SET_FEED = 'application/vnd.com.runkeeper.WeightSetFeed+json';

    const RETURN_SUCCESS = 1;

    const RETURN_ERROR_EDIT_BAD_FIELD = 2;

    const RETURN_ERROR_SAVE = 3;

    const URI_BACKGROUND_ACTIVITIES = '/backgroundActivities';

    const URI_DIABETES = '/diabetes';

    const URI_FITNESS_ACTIVITIES = '/fitnessActivities';

    const URI_GENERAL_MEASUREMENTS = '/generalMeasurements';

    const URI_NUTRITION = '/nutrition';

    const URI_PROFILE = '/profile';

    const URI_RECORDS = '/records';

    const URI_SETTINGS = '/settings';

    const URI_SLEEP = '/sleep';

    const URI_STRENGTH_TRAINING_ACTIVITIES = '/strengthTrainingActivities';

    const URI_TEAM = '/team';

    const URI_USER = '/user';

    const URI_WEIGHT = '/weight';

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
     * @return string[]
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

    private function requestGet($contentType, $uri)
    {
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = $contentType;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders
        ));
        return $this->treatResult($oResponse);
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
        $oResponse = $this->oClient->request('PUT', self::URI_PROFILE, array(
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
        return $this->requestGet(self::CONTENT_TYPE_SETTINGS, self::URI_SETTINGS);
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
        $oResponse = $this->oClient->request('PUT', self::URI_SETTINGS, array(
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
        $url = self::URI_FITNESS_ACTIVITIES;
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
        return $this->requestGet(self::CONTENT_TYPE_FITNESS_ACTIVITY, $uri);
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
        return $this->requestGet(self::CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY, $uri);
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
    public function addFitnessActivity(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_FITNESS_ACTIVITIES, array(
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
        $url = self::URI_STRENGTH_TRAINING_ACTIVITIES;
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
    public function addStrengthActivity(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_STRENGTH_TRAINING_ACTIVITIES, array(
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
        return $this->requestGet(self::CONTENT_TYPE_STRENGTH_ACTIVITY, $uri);
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
        $url = self::URI_WEIGHT;
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
        return $this->requestGet(self::CONTENT_TYPE_WEIGHT_SET, $uri);
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
    public function addWeightSet(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_WEIGHT, array(
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
        $url = self::URI_BACKGROUND_ACTIVITIES;
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
    public function addBackgroundActivitySet(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_BACKGROUND_ACTIVITIES, array(
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
        $url = self::URI_SLEEP;
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
    public function addSleepSet(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_SLEEP, array(
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
        $url = self::URI_NUTRITION;
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
    public function addNutritionSet(array $arrayData)
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
        $oResponse = $this->oClient->request('POST', self::URI_NUTRITION, array(
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
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#feed
     * @return mixed
     */
    public function getGeneralMeasurementSetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = self::URI_GENERAL_MEASUREMENTS;
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
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#past
     * @return mixed
     */
    public function setGeneralMeasurementSet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'systolic',
                'diastolic',
                'total_cholesterol',
                'hdl',
                'ldl',
                'vitamin_d',
                'hscrp',
                'crp',
                'tsh',
                'uric_acid',
                'resting_heartrate',
                'blood_calcium',
                'blood_magnesium',
                'creatine_kinase',
                'blood_vitamin_b12',
                'blood_folic_acid',
                'ferritin',
                'il6',
                'testosterone',
                'blood_potassium',
                'blood_sodium',
                'blood_zinc',
                'blood_chromium',
                'white_cell_count'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_GENERAL_MEASUREMENT_SET;
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
     * @link https://runkeeper.com/developer/healthgraph/general-measurement-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addGeneralMeasurementSet(array $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'systolic',
                'diastolic',
                'total_cholesterol',
                'hdl',
                'ldl',
                'vitamin_d',
                'hscrp',
                'crp',
                'tsh',
                'uric_acid',
                'resting_heartrate',
                'blood_calcium',
                'blood_magnesium',
                'creatine_kinase',
                'blood_vitamin_b12',
                'blood_folic_acid',
                'ferritin',
                'il6',
                'testosterone',
                'blood_potassium',
                'blood_sodium',
                'blood_zinc',
                'blood_chromium',
                'white_cell_count',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_GENERAL_MEASUREMENT_SET;
        $oResponse = $this->oClient->request('POST', self::URI_GENERAL_MEASUREMENTS, array(
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
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#feed
     * @return mixed
     */
    public function getDiabeteMeasurementSetFeed($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = self::URI_DIABETES;
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
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#past
     * @return mixed
     */
    public function setDiabeteMeasurementSet($uri, $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'fasting_plasma_glucose_test',
                'oral_glucose_tolerance_test',
                'random_plasma_glucose_test',
                'hemoglobin_a1c',
                'insulin',
                'c_peptide',
                'triglyceride'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_DIABETE_MEASUREMENT_SET;
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
     * @link https://runkeeper.com/developer/healthgraph/diabetes-sets#new
     * @param array $arrayData            
     * @return string
     */
    public function addDiabeteMeasurementSet(array $arrayData)
    {
        if (empty($arrayData)) {
            return self::RETURN_SUCCESS;
        }
        
        foreach ($arrayData as $key => $value) {
            if (! in_array($key, array(
                'timestamp',
                'fasting_plasma_glucose_test',
                'oral_glucose_tolerance_test',
                'random_plasma_glucose_test',
                'hemoglobin_a1c',
                'insulin',
                'c_peptide',
                'triglyceride',
                'white_cell_count',
                'post_to_facebook',
                'post_to_twitter'
            ))) {
                return self::RETURN_ERROR_EDIT_BAD_FIELD;
            }
        }
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_NEW_DIABETE_MEASUREMENT_SET;
        $oResponse = $this->oClient->request('POST', self::URI_DIABETES, array(
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
        $url = self::URI_RECORDS;
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
     * @link https://runkeeper.com/developer/healthgraph/friends#feed
     * @return mixed
     */
    public function getFriends($numPage = null, $pageSize = null)
    {
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = self::CONTENT_TYPE_TEAM_FEED;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        // URL
        $url = self::URI_TEAM;
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
