<?php
require_once (__DIR__ . '/../../../../../../../autoloader.php');

class VKAuthUnitTest extends PHPUnit\Framework\TestCase
{

    /**
     * Method returns fake settings
     *
     * @return array fake settings
     */
    protected function getSettings(): array
    {
        return ([
            'client_id' => 1,
            'client_secret' => 2,
            'redirect_uri' => 3
        ]);
    }

    /**
     * Testing get_user_info_uri
     */
    public function testGetUerInfoUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertContains('/api.vk.com/method/users.get?v=5.0&', $Auth->get_user_info_uri());
    }

    /**
     * Testing get_token_uri
     */
    public function testGetTokenUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertContains('/oauth.vk.com/access_token?v=5.0&', $Auth->get_token_uri());
    }

    /**
     * Testing get_desired_fields
     */
    public function testGetDesiredFields()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body
        $Fields = $Auth->get_desired_fields();

        // assertions
        $this->assertContains('id', $Fields);
        $this->assertContains('first_name', $Fields);
        $this->assertContains('last_name', $Fields);
        $this->assertContains('email', $Fields);
        $this->assertContains('photo_100', $Fields);
    }

    /**
     * Testing dispatch_user_info
     */
    public function testDispatchUserInfo()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body
        $Result = $Auth->dispatch_user_info([
            'response' => [
                [
                    'id' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'photo_100' => '',
                    'email' => ''
                ]
            ]
        ]);

        // assertions
        $this->assertArrayHasKey('id', $Result, 'id was not found');
        $this->assertArrayHasKey('first_name', $Result, 'first_name was not found');
        $this->assertArrayHasKey('last_name', $Result, 'last_name was not found');
        $this->assertArrayHasKey('picture', $Result, 'picture was not found');
        $this->assertArrayHasKey('email', $Result, 'email was not found');
    }
}

?>