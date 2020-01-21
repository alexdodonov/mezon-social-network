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
class BaseAuth
{

    /**
     * Authorization settings.
     *
     * @var array
     */
    var $Settings = [];

    /**
     * Fetched user's info.
     *
     * @var array
     */
    var $UserInfo;

    /**
     * Constructor.
     *
     * @param array $Settings
     *            - Connection settings.
     */
    public function __construct(array $Settings)
    {
        if (isset($Settings['client_id'], $Settings['client_secret'], $Settings['redirect_uri'])) {
            $this->Settings = $Settings;
        }
    }

    /**
     * Method returns request result
     *
     * @param string $URL
     * @return string Request result
     */
    protected function get_request(string $URL): string
    {
        // @codeCoverageIgnoreStart
        return (file_get_contents($URL));
        // @codeCoverageIgnoreEnd
    }

    /**
     * Metthod tryes to authorize user.
     *
     * @param string $Code
     *            - Code.
     * @return boolean True on success. False otherwise.
     */
    public function auth(string $Code): bool
    {
        if ($Code && $this->Settings) {

            $Params = $this->get_token_params($Code);

            $Token = $this->request_token($Params);

            if (isset($Token['access_token'])) {
                $Query = urldecode(http_build_query([

                    'access_token' => $Token['access_token'],
                    'fields' => $this->get_desired_fields()
                ]));

                $this->UserInfo = json_decode($this->get_request($this->get_user_info_uri($Token['access_token']) . $Query), true);

                $this->UserInfo = $this->dispatch_user_info($this->UserInfo);

                if (isset($this->UserInfo['id'])) {
                    return (true);
                }
            }
        }

        return (false);
    }

    /**
     * Method returns authorization URL.
     *
     * @return string Authorization url.
     */
    public function get_link(): string
    {
        if (count($this->Settings)) {

            $Query = urldecode(http_build_query([

                'client_id' => $this->Settings['client_id'],
                'redirect_uri' => $this->Settings['redirect_uri'],
                'response_type' => 'code'
            ]));

            return ($this->get_oauth_uri() . $Query);
        }

        throw (new \Exception('Social network\'s authorization URL was not found.'));
    }

    /**
     * Method returns URL wich generates tokens.
     *
     * @return string URL
     */
    protected function get_oauth_uri(): string
    {
        return ('http://oauth-uri');
    }

    /**
     * Method return URL wich provides user's info
     *
     * @param string $Token
     *            Token
     * @return string URL
     */
    public function get_user_info_uri(string $Token = ''): string
    {
        return ('http://user-info-uri/?' . $Token);
    }

    /**
     * Method returns token URL
     *
     * @return string URL
     */
    public function get_token_uri(): string
    {
        return ('http://token-uri');
    }

    /**
     * Method returns a list of desired fields
     *
     * @return string Comma separated of the desired fields
     */
    public function get_desired_fields(): string
    {
        return ('desired,fields');
    }

    /**
     * Method dispatches user info
     *
     * @param array $UserInfo
     *            User info got from social network
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture
     */
    public function dispatch_user_info(array $UserInfo): array
    {
        $UserInfo['picture'] = $UserInfo['picture']['data']['url'];

        return ($UserInfo);
    }

    /**
     * Method returns params for getting token
     *
     * @param string $Code
     *            Access code
     * @return array Params
     */
    public function get_token_params(string $Code): array
    {
        return ([
            'client_id' => $this->Settings['client_id'],
            'redirect_uri' => $this->Settings['redirect_uri'],
            'client_secret' => $this->Settings['client_secret'],
            'code' => $Code
        ]);
    }

    /**
     * Method requests token from server
     *
     * @param array $Params
     *            Request params
     * @return array Token data
     */
    public function request_token(array $Params): array
    {
        $Query = urldecode(http_build_query($Params));

        $Token = json_decode(file_get_contents($this->get_token_uri() . $Query), true);

        return ($Token);
    }
}

?>