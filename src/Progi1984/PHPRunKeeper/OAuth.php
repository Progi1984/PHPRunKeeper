<?php
namespace Progi1984\PHPRunKeeper;

use GuzzleHttp\Client as HttpClient;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

/**
 * RunKeeper OAuth
 * The RunKeeper implementation of the OAuth client
 *
 * @see : https://github.com/thephpleague/oauth2-client
 * @author Progi1984
 */
class OAuth extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = 'https://runkeeper.com';

    /**
     * Api domain
     *
     * @var string
     */
    public $apiDomain = 'https://api.runkeeper.com';

    public function __construct(array $options = [], array $collaborators = [])
    {
        $collaborators['httpClient'] = new HttpClient(array(
            'verify' => false
        ));
        parent::__construct($options, $collaborators);
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/apps/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params            
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/apps/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token            
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->apiDomain . '/profile';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @link https://runkeeper.com/developer/healthgraph/error-messages
     * @throws IdentityProviderException
     * @param ResponseInterface $response            
     * @param array $data Parsed response data
     * @return void
     * @see \League\OAuth2\Client\Provider\AbstractProvider::checkResponse()
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(isset($data['reason']) ? $data['reason'] : $response->getReasonPhrase(), $response->getStatusCode(), $response);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response            
     * @param AccessToken $token            
     * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new RKResourceOwner($response);
    }

    /**
     * (non-PHPdoc)
     * @see \League\OAuth2\Client\Provider\AbstractProvider::parseResponse()
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $content = (string) $response->getBody();
        $type = $this->getContentType($response);
        parse_str(str_replace(';', '&', $type), $type);
        if (isset($type['charset']) && $type['charset'] == 'ISO-8859-1') {
            $content = utf8_encode($content);
        }
        try {
            return $this->parseJson($content);
        } catch (UnexpectedValueException $e) {
            if (strpos($type, 'json') !== false) {
                throw $e;
            }
            
            return $content;
        }
    }
}