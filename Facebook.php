<?php
namespace Mezon\SocialNetwork\Auth;

/**
 * Class FacebookAuth
 *
 * @package     Auth
 * @subpackage  FacebookAuth
 * @author      Dodonov A.A.
 * @version     v.1.0 (2019/08/17)
 * @copyright   Copyright (c) 2019, aeon.org
 */

/**
 * Class provides integration with Facebook.
 *
 * @author Dodonov A.A.
 */
class Facebook extends \Mezon\SocialNetwork\BaseAuth
{

    /**
     * Method returns URL wich generates tokens.
     *
     * @return string URL
     */
    protected function get_oauth_uri(): string
    {
        return ('https://www.facebook.com/dialog/oauth?');
    }

    /**
     * Method return URL wich provides user's info.
     *
     * @param string $Token
     *            - Token;
     * @return string URL
     */
    public function get_user_info_uri(string $Token = ''): string
    {
        return ('https://graph.facebook.com/me?');
    }

    /**
     * Method returns.
     *
     * @return string URL
     */
    public function get_token_uri(): string
    {
        return ('https://graph.facebook.com/oauth/access_token?');
    }

    /**
     * Method returns a list of desired fields.
     *
     * @return string Comma separated of the desired fields.
     */
    public function get_desired_fields(): string
    {
        return ('id,first_name,last_name,email,picture.width(120).height(120)');
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
        $UserInfo['email'] = $UserInfo['email'] ?? '';
        $UserInfo['picture'] = $UserInfo['picture']['data']['url'];

        return ($UserInfo);
    }
}

?>