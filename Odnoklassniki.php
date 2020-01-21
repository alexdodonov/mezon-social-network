<?php
namespace Mezon\SocialNetwork\Auth;

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
class Odnoklassniki extends \Mezon\SocialNetwork\BaseAuth
{

    /**
     * Method returns URL wich generates tokens
     *
     * @return string URL
     */
    protected function get_oauth_uri(): string
    {
        return ('https://connect.ok.ru/oauth/authorize?scope=VALUABLE_ACCESS;PHOTO_CONTENT&');
    }

    /**
     * Method return URL wich provides user's info
     *
     * @param string $Token
     *            - Token
     * @return string URL
     */
    public function get_user_info_uri(string $Token = ''): string
    {
        $Signature = md5('application_key=' . $this->Settings['client_public'] . 'fields=' . $this->get_desired_fields() . 'format=jsonmethod=users.getCurrentUser' . md5($Token . $this->Settings['client_secret']));

        return ('http://api.odnoklassniki.ru/fb.do?application_key=' . $this->Settings['client_public'] . '&format=json&method=users.getCurrentUser&sig=' . $Signature . '&');
    }

    /**
     * Method returns
     *
     * @return string URL
     */
    public function get_token_uri(): string
    {
        return ('http://api.odnoklassniki.ru/oauth/token.do?grant_type=authorization_code&');
    }

    /**
     * Method returns a list of desired fields
     *
     * @return string Comma separated of the desired fields
     */
    public function get_desired_fields(): string
    {
        return ('UID,LOCALE,FIRST_NAME,LAST_NAME,EMAIL,PIC190X190,PIC640X480');
    }

    /**
     * Method dispatches user info
     *
     * @param array $UserInfo
     *            - User info got from social network
     * @return array Dispatched user info. Must be as array with keys id, first_name, last_name, email, picture
     */
    public function dispatch_user_info(array $UserInfo): array
    {
        $UserInfo['email'] = $UserInfo['email'] ?? '';

        $Return = [
            'id' => $UserInfo['uid'],
            'first_name' => $UserInfo['first_name'],
            'last_name' => $UserInfo['last_name'],
            'picture' => $UserInfo['pic190x190'],
            'email' => $UserInfo['email']
        ];

        return ($Return);
    }

    /**
     * Method returns params for getting token
     *
     * @param string $Code
     *            - Access code
     * @return array Params
     */
    public function get_token_params(string $Code): array
    {
        return ([
            'client_id' => $this->Settings['client_id'],
            'redirect_uri' => $this->Settings['redirect_uri'],
            'client_secret' => $this->Settings['client_secret'],
            'grant_type' => 'authorization_code',
            'code' => $Code
        ]);
    }

    /**
     * Method requests token from server
     *
     * @param array $Params
     *            - Request params
     * @return array Token data
     */
    public function request_token(array $Params): array
    {
        $Result = \Mezon\CustomClient\CurlWrapper::send_request('http://api.odnoklassniki.ru/oauth/token.do', [], 'POST', $Params);

        $Token = json_decode($Result[0], true);

        return ($Token);
    }
}

?>