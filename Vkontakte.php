<?php
namespace Mezon\SocialNetwork\Auth;

/**
 * Class Vkontakte
 *
 * @package BaseAuth
 * @subpackage Vkontakte
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class provides integration with VK.
 *
 * @author Dodonov A.A.
 */
class Vkontakte extends \Mezon\SocialNetwork\BaseAuth
{

    /**
     * Method returns URL wich generates tokens.
     *
     * @return string URL
     */
    public function getOauthUri(): string
    {
        return 'https://oauth.vk.com/authorize?v=5.0&';
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
        return 'https://api.vk.com/method/users.get?v=5.0&';
    }

    /**
     * Method returns.
     *
     * @return string URL
     */
    public function getTokenUri(): string
    {
        return 'https://oauth.vk.com/access_token?v=5.0&';
    }

    /**
     * Method returns a list of desired fields.
     *
     * @return string Comma separated of the desired fields.
     */
    public function getDesiredFields(): string
    {
        return 'uid,first_name,last_name,email,photo_100';
    }

    /**
     * Method dispatches user info.
     *
     * @param array $userInfo
     *            - User info got from social network.
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture.
     */
    public function dispatchUserInfo(array $userInfo): array
    {
        $response = $userInfo['response'][0];

        $response['email'] = $response['email'] ?? '';

        return [
            'id' => $response['id'],
            'first_name' => $response['first_name'],
            'last_name' => $response['last_name'],
            'picture' => $response['photo_100'],
            'email' => $response['email']
        ];
    }
}
