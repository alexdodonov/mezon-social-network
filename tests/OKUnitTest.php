<?php

class OKUnitTest extends PHPUnit\Framework\TestCase
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
            'redirect_uri' => 3,
            'client_public' => 4
        ]);
    }

    /**
     * Testing getUserInfoUri
     */
    public function testGetUserInfoUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Odnoklassniki($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/api.odnoklassniki.ru/fb.do?application_key=', $Auth->getUserInfoUri());
        $this->assertStringContainsString('?application_key=4', $Auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Odnoklassniki($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/api.odnoklassniki.ru/oauth/token.do?grant_type=authorization_code&', $Auth->getTokenUri());
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Odnoklassniki($this->getSettings());

        // test body
        $Result = $Auth->dispatchUserInfo([
            'uid' => '',
            'first_name' => '',
            'last_name' => '',
            'pic190x190' => '',
            'email' => ''
        ]);

        // assertions
        $this->assertArrayHasKey('id', $Result, 'id was not found');
        $this->assertArrayHasKey('first_name', $Result, 'first_name was not found');
        $this->assertArrayHasKey('last_name', $Result, 'last_name was not found');
        $this->assertArrayHasKey('picture', $Result, 'picture was not found');
        $this->assertArrayHasKey('email', $Result, 'email was not found');
    }

    /**
     * Testing getTokenParams method
     */
    public function testGetTokenParams()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\Auth\Odnoklassniki($this->getSettings());

        // test body
        $Params = $Auth->getTokenParams(123);

        // assertions
        $this->assertEquals(1, $Params['client_id'], 'Invalid "client_id"');
        $this->assertEquals(2, $Params['client_secret'], 'Invalid "client_secret"');
        $this->assertEquals(3, $Params['redirect_uri'], 'Invalid "redirect_uri"');
        $this->assertEquals('authorization_code', $Params['grant_type'], 'Invalid "grant_type"');
        $this->assertEquals(123, $Params['code'], 'Invalid "code"');
    }
}

?>