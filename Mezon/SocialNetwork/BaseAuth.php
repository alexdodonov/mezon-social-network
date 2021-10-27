<?php
namespace Mezon\SocialNetwork;

/**
 * Class SocialNetworkAuth
 *
 * @package SocialNetwork
 * @subpackage SocialNetworkAuth
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class provides integration with social networks authorization APIs.
 *
 * @author Dodonov A.A.
 */
abstract class BaseAuth
{

    /**
     * Authorization settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Fetched user's info.
     *
     * @var array
     */
    protected $userInfo = [];

    /**
     * Constructor.
     *
     * @param array $settings
     *            - Connection settings.
     */
    public function __construct(array $settings)
    {
        if (isset($settings['client_id'], $settings['client_secret'], $settings['redirect_uri'])) {
            $this->settings = $settings;
        }
    }

    /**
     * Method returns request result
     *
     * @param string $URL
     * @return string Request result
     */
    protected function getRequest(string $url): string
    {
        // @codeCoverageIgnoreStart
        return file_get_contents($url);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Metthod tryes to authorize user.
     *
     * @param string $Code
     *            - Code.
     * @return boolean True on success. False otherwise.
     */
    public function auth(string $code): bool
    {
        if ($code && ! empty($this->settings)) {

            $params = $this->getTokenParams($code);

            $token = $this->requestToken($params);

            if (isset($token['access_token'])) {
                $query = http_build_query(
                    [

                        'access_token' => $token['access_token'],
                        'fields' => $this->getDesiredFields()
                    ]);

                $query = urldecode($query);

                $this->userInfo = json_decode(
                    $this->getRequest($this->getUserInfoUri($token['access_token']) . $query),
                    true);

                $this->userInfo = $this->dispatchUserInfo($this->userInfo);

                if (isset($this->userInfo['id'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Method returns authorization URL.
     *
     * @return string Authorization url.
     */
    public function getLink(): string
    {
        if (! empty($this->settings)) {
            $query = http_build_query(
                [

                    'client_id' => $this->settings['client_id'],
                    'redirect_uri' => $this->settings['redirect_uri'],
                    'response_type' => 'code'
                ]);

            $query = urldecode($query);

            return $this->getOauthUri() . $query;
        }

        throw (new \Exception('Social network\'s authorization URL was not found.'));
    }

    /**
     * Method returns URL wich generates tokens.
     *
     * @return string URL
     */
    abstract public function getOauthUri(): string;

    /**
     * Method return URL wich provides user's info
     *
     * @param string $token
     *            Token
     * @return string URL
     */
    abstract public function getUserInfoUri(string $token = ''): string;

    /**
     * Method returns token URL
     *
     * @return string URL
     */
    abstract public function getTokenUri(): string;

    /**
     * Method returns a list of desired fields
     *
     * @return string Comma separated of the desired fields
     */
    public function getDesiredFields(): string
    {
        return 'desired,fields';
    }

    /**
     * Method dispatches user info
     *
     * @param array $userInfo
     *            User info got from social network
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture
     */
    public function dispatchUserInfo(array $userInfo): array
    {
        $userInfo['picture'] = $userInfo['picture']['data']['url'];

        return $userInfo;
    }

    /**
     * Method returns params for getting token
     *
     * @param string $code
     *            Access code
     * @return array Params
     */
    public function getTokenParams(string $code): array
    {
        return [
            'client_id' => $this->settings['client_id'],
            'redirect_uri' => $this->settings['redirect_uri'],
            'client_secret' => $this->settings['client_secret'],
            'code' => $code
        ];
    }

    /**
     * Method requests token from server
     *
     * @param array $params
     *            Request params
     * @return array Token data
     */
    public function requestToken(array $params): array
    {
        $query = urldecode(http_build_query($params));

        return json_decode(file_get_contents($this->getTokenUri() . $query), true);
    }

    /**
     * Method returns settings
     *
     * @return array settigs
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}
