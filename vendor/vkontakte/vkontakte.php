<?php
namespace Mezon\SocialNetwork\BaseAuth;

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
    protected function get_oauth_uri(): string
    {
        return ('https://oauth.vk.com/authorize?v=5.0&');
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
        return ('https://api.vk.com/method/users.get?v=5.0&');
    }

    /**
     * Method returns.
     *
     * @return string URL
     */
    public function get_token_uri(): string
    {
        return ('https://oauth.vk.com/access_token?v=5.0&');
    }

    /**
     * Method returns a list of desired fields.
     *
     * @return string Comma separated of the desired fields.
     */
    public function get_desired_fields(): string
    {
        return ('uid,first_name,last_name,email,photo_100');
    }

    /**
     * Method dispatches user info.
     *
     * @param array $UserInfo
     *            - User info got from social network.
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture.
     */
    public function dispatch_user_info(array $UserInfo): array
    {
        $Response = $UserInfo['response'][0];

        $Response['email'] = $Response['email'] ?? '';

        $Return = [
            'id' => $Response['id'],
            'first_name' => $Response['first_name'],
            'last_name' => $Response['last_name'],
            'picture' => $Response['photo_100'],
            'email' => $Response['email']
        ];

        return ($Return);
    }
}

?>