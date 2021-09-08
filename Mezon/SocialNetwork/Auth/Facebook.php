<?php
namespace Mezon\SocialNetwork\Auth;

use Mezon\SocialNetwork\BaseAuth;

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
class Facebook extends BaseAuth
{

    /**
     * Method returns URL wich generates tokens.
     *
     * @return string URL
     */
    public function getOauthUri(): string
    {
        return 'https://www.facebook.com/dialog/oauth?';
    }

    /**
     * Method return URL wich provides user's info.
     *
     * @param string $token
     *            - Token;
     * @return string URL
     */
    public function getUserInfoUri(string $token = ''): string
    {
        return 'https://graph.facebook.com/me?';
    }

    /**
     * Method returns.
     *
     * @return string URL
     */
    public function getTokenUri(): string
    {
        return 'https://graph.facebook.com/oauth/access_token?';
    }

    /**
     * Method returns a list of desired fields.
     *
     * @return string Comma separated of the desired fields.
     */
    public function getDesiredFields(): string
    {
        return 'id,first_name,last_name,email,picture.width(120).height(120)';
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
        $userInfo['email'] = $userInfo['email'] ?? '';
        $userInfo['picture'] = $userInfo['picture']['data']['url'];

        return $userInfo;
    }
}
