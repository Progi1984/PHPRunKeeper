<?php
namespace Progi1984\PHPRunKeeper;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class RKResourceOwner implements ResourceOwnerInterface
{

    /**
     * Domain
     *
     * @var string
     */
    protected $domain;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return isset($this->response['id']) ? $this->response['id'] : null;
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return isset($this->response['email']) ? $this->response['email'] : null;
    }

    /**
     * Get resource owner name
     *
     * @return string|null
     */
    public function getName()
    {
        return isset($this->response['name']) ? $this->response['name'] : null;
    }

    /**
     * Get resource owner nickname
     *
     * @return string|null
     */
    public function getNickname()
    {
        return isset($this->response['login']) ? $this->response['login'] : null;
    }

    /**
     * Get resource owner url
     *
     * @return string|null
     */
    public function getUrl()
    {
        return isset($this->response['profile']) ? $this->response['profile'] : null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
