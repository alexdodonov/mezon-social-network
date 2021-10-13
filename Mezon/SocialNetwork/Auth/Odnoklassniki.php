<?php
namespace Mezon\SocialNetwork\Auth;

use Mezon\SocialNetwork\BaseAuth;

/**
 * Class Odnoklassniki
 *
 * @package BaseAuth
 * @subpackage Odnoklassniki
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class provides integration with OK
 *
 * @author Dodonov A.A.
 */
class Odnoklassniki extends BaseAuth
{

    /**
     * Method returns URL wich generates tokens
     *
     * @return string URL
     */
    public function getOauthUri(): string
    {
        return 'https://connect.ok.ru/oauth/authorize?scope=VALUABLE_ACCESS;PHOTO_CONTENT&';
    }

    /**
     *
     * {@inheritdoc}
     * @see \Mezon\SocialNetwork\BaseAuth::getUserInfoUri()
     */
    public function getUserInfoUri(string $token = ''): string
    {
        $signature = md5(
            'application_key=' . $this->settings['client_public'] . 'fields=' . $this->getDesiredFields() .
            'format=jsonmethod=users.getCurrentUser' . md5($token . $this->settings['client_secret']));

        return 'http://api.odnoklassniki.ru/fb.do?application_key=' . $this->settings['client_public'] .
            '&format=json&method=users.getCurrentUser&sig=' . $signature . '&';
    }

    /**
     * Method returns
     *
     * @return string URL
     */
    public function getTokenUri(): string
    {
        return 'http://api.odnoklassniki.ru/oauth/token.do?grant_type=authorization_code&';
    }

    /**
     * Method returns a list of desired fields
     *
     * @return string Comma separated of the desired fields
     */
    public function getDesiredFields(): string
    {
        return 'UID,LOCALE,FIRST_NAME,LAST_NAME,EMAIL,PIC190X190,PIC640X480';
    }

    /**
     * Method dispatches user info
     *
     * @param array $UserInfo
     *            - User info got from social network
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture
     */
    public function dispatchUserInfo(array $userInfo): array
    {
        $userInfo['email'] = $userInfo['email'] ?? '';

        $return = [
            'id' => $userInfo['uid'],
            'first_name' => $userInfo['first_name'],
            'last_name' => $userInfo['last_name'],
            'picture' => $userInfo['pic190x190'],
            'email' => $userInfo['email']
        ];

        return $return;
    }

    /**
     * Method returns params for getting token
     *
     * @param string $code
     *            - Access code
     * @return array Params
     */
    public function getTokenParams(string $code): array
    {
        return [
            'client_id' => $this->settings['client_id'],
            'redirect_uri' => $this->settings['redirect_uri'],
            'client_secret' => $this->settings['client_secret'],
            'grant_type' => 'authorization_code',
            'code' => $code
        ];
    }

    /**
     * Method requests token from server
     *
     * @param array $params
     *            - Request params
     * @return array Token data
     */
    public function requestToken(array $params): array
    {
        $result = \Mezon\CustomClient\CurlWrapper::sendRequest(
            'http://api.odnoklassniki.ru/oauth/token.do',
            [],
            'POST',
            $params);

        return json_decode($result[0], true);
    }
}
