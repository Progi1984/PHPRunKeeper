<?php
namespace Progi1984;

use \League\OAuth2\Client\Token\AccessToken as OauthAccessToken;
use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Psr7\Response as HttpResponse;

class PHPRunKeeper
{

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
        echo '<!--' . var_export($oResponse->getHeaders(), true) . '--!>';
        $type = $oResponse->getHeader('Content-Type');
        $type = reset($type);
        $type = str_replace(';', '&', $type);
        parse_str($type, $arrType);
        if (isset($arrType['charset']) && $arrType['charset'] == 'ISO-8859-1') {
            $content = utf8_encode($content);
        }
        return json_decode($content, true);
    }

    const CONTENT_TYPE_PROFILE = 'application/vnd.com.runkeeper.Profile+json';

    const CONTENT_TYPE_SETTINGS = 'application/vnd.com.runkeeper.Settings+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_FEED = 'application/vnd.com.runkeeper.FitnessActivityFeed+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY = 'application/vnd.com.runkeeper.FitnessActivity+json';

    const CONTENT_TYPE_FITNESS_ACTIVITY_SUMMARY = 'application/vnd.com.runkeeper.FitnessActivitySummary+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY_FEED = 'application/vnd.com.runkeeper.StrengthTrainingActivityFeed+json';

    const CONTENT_TYPE_STRENGTH_ACTIVITY = 'application/vnd.com.runkeeper.StrengthTrainingActivity+json';

    const CONTENT_TYPE_WEIGHT_SET_FEED = 'application/vnd.com.runkeeper.WeightSetFeed+json';

    const CONTENT_TYPE_WEIGHT_SET = 'application/vnd.com.runkeeper.WeightSet+json';
    const CONTENT_TYPE_RECORDS = 'application/vnd.com.runkeeper.Records+json';

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
