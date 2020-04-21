<?php

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
     * Testing getUserInfoUri
     */
    public function testGetUerInfoUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/api.vk.com/method/users.get?v=5.0&', $Auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/oauth.vk.com/access_token?v=5.0&', $Auth->getTokenUri());
    }

    /**
     * Testing getDesiredFields
     */
    public function testGetDesiredFields()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body
        $Fields = $Auth->getDesiredFields();

        // assertions
        $this->assertStringContainsString('id', $Fields);
        $this->assertStringContainsString('first_name', $Fields);
        $this->assertStringContainsString('last_name', $Fields);
        $this->assertStringContainsString('email', $Fields);
        $this->assertStringContainsString('photo_100', $Fields);
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Vkontakte($this->getSettings());

        // test body
        $Result = $Auth->dispatchUserInfo([
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
