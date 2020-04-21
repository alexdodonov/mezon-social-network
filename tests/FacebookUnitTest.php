<?php

class FacebookUnitTest extends PHPUnit\Framework\TestCase
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
    public function testGetUserInfoUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Facebook($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/graph.facebook.com/me?', $Auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Facebook($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/graph.facebook.com/oauth/access_token?', $Auth->getTokenUri());
    }

    /**
     * Testing getDesiredFields
     */
    public function testGetDesiredFields()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Facebook($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('id,first_name,last_name,email,picture.width(120).height(120)', $Auth->getDesiredFields());
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Facebook($this->getSettings());

        // test body
        $Result = $Auth->dispatchUserInfo([
            'id' => '',
            'first_name' => '',
            'last_name' => '',
            'pic190x190' => '',
            'picture' => [
                'data' => [
                    'url' => 'url'
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
