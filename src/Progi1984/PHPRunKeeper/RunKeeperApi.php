<?php
namespace Progi1984\PHPRunKeeper;

use \GuzzleHttp\Client as HttpClient;
use Progi1984\PHPRunKeeper;
/**
 * @author Progi1984
 */
class RunKeeperApi
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

    protected $editBkgActivity = array(
        'calories_burned',
        'steps'
    );
    
    protected $editDiabeteMeasure = array(
        'fasting_plasma_glucose_test',
        'oral_glucose_tolerance_test',
        'random_plasma_glucose_test',
        'hemoglobin_a1c',
        'insulin',
        'c_peptide',
        'triglyceride'
    );
    
    protected $editFitnessActivity = array(
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
    );
    
    protected $editFitnessActSum = array(
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
    );
    
    protected $editGenMeasurement = array(
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
    );
    
    protected $editNutrition = array(
        'calories',
        'carbohydrates',
        'fat',
        'fiber',
        'protein',
        'sodium',
        'water',
        'meal'
    );
    
    protected $editProfile = array(
        'athlete_type'
    );
    
    protected $editSettings = array(
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
    );
    
    protected $editSleep = array(
        'total_sleep',
        'deep',
        'rem',
        'light',
        'awake',
        'times_woken'
    );
    
    protected $editStrengthActivity = array(
        'start_time',
        'total_calories',
        'notes',
        'exercises'
    );
    
    protected $editWeight = array(
        'weight',
        'free_mass',
        'fat_percent',
        'mass_weight',
        'bmi'
    );
    
    /**
     *
     * @var string
     */
    protected $endPoint = 'https://api.runkeeper.com/';
    
    /**
     *
     * @var HttpClient
     */
    protected $oClient;

    /**
     * Request GET
     *
     * @param string $contentType
     * @param string $uri
     */
    protected function requestGet($contentType, $uri, $numPage = null, $pageSize = null)
    {
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = $contentType;
        $arrayHeaders['Accept'] = $arrayHeaders['Content-Type'];
        $oResponse = $this->oClient->request('GET', $uri, array(
            'headers' => $arrayHeaders,
            'query' => array(
                'page' => $numPage,
                'pageSize' => $pageSize
            )
        ));
    
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
     * Request PUT
     *
     * @param string $contentType
     * @param string $uri
     * @param array $arrayData
     * @param string[] $arrayEdit
     */
    protected function requestPut($contentType, $uri, array $arrayData, $arrayEdit)
    {
        $arrayDiff = array_diff(array_keys($arrayData), $arrayEdit);
        if (!empty($arrayDiff)) {
            return self::RETURN_ERROR_EDIT_BAD_FIELD;
        }
    
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = $contentType;
        $oResponse = $this->oClient->request('PUT', $uri, array(
            'headers' => $arrayHeaders,
            'json' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 200) {
            return self::RETURN_SUCCESS;
        }
        return self::RETURN_ERROR_SAVE;
    }
    
    /**
     *
     * @param string $contentType
     * @param string $uri
     * @param array $arrayData
     * @param string[] $arrayAdd
     */
    protected function requestPost($contentType, $uri, array $arrayData, $arrayAdd)
    {
        $arrayDiff = array_diff(array_keys($arrayData), $arrayAdd);
        if (!empty($arrayDiff)) {
            return self::RETURN_ERROR_EDIT_BAD_FIELD;
        }
    
        // Headers
        $arrayHeaders = $this->getHeaders();
        $arrayHeaders['Content-Type'] = $contentType;
        $oResponse = $this->oClient->request('POST', $uri, array(
            'headers' => $arrayHeaders,
            'form_params' => $arrayData
        ));
        if ($oResponse->getStatusCode() == 201) {
            return $oResponse->getHeader('Location');
        }
        return self::RETURN_ERROR_SAVE;
    }
}
