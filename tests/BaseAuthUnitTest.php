<?php

require_once (__DIR__ . '/../vendor/autoload.php');

class BaseAuthUnitTest extends PHPUnit\Framework\TestCase
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
     * Testing constructor
     */
    public function testConstructor()
    {
        // setup and test body
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // assertions
        $this->assertEquals(3, count($Auth->Settings), 'Setting were not set');
    }

    /**
     * Testing get_link
     */
    public function testGetLink()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // test body
        $Link = $Auth->get_link();

        // assertions
        $this->assertStringContainsString('http://oauth-uriclient_id=1&redirect_uri=3&response_type=code', $Link, 'Invalid link was generated');
    }

    /**
     * Testing get_link exception
     */
    public function testGetLinkException()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth([]);

        try {
            // test body and assertions
            $Auth->get_link();
            $this->fail('Exception must be thrown');
        } catch (Exception $e) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * Testing get_user_info_uri
     */
    public function testGetUserInfoUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // test body
        $Link = $Auth->get_user_info_uri();

        // assertions
        $this->assertStringContainsString('://user-info-uri/?', $Link, 'Invalid user info URI');
    }

    /**
     * Testing get_token_params method
     */
    public function testGetTokenParams()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // test body
        $Params = $Auth->get_token_params(123);

        // assertions
        $this->assertEquals(1, $Params['client_id'], 'Invalid "client_id"');
        $this->assertEquals(2, $Params['client_secret'], 'Invalid "client_secret"');
        $this->assertEquals(3, $Params['redirect_uri'], 'Invalid "redirect_uri"');
        $this->assertEquals(123, $Params['code'], 'Invalid "code"');
    }

    /**
     * Testing get_token_uri
     */
    public function testGetTokenUri()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // test body
        $Link = $Auth->get_token_uri();

        // assertions
        $this->assertStringContainsString('://token-uri', $Link, 'Invalid token URI');
    }

    /**
     * Testing get_desired_fields
     */
    public function testGetDesiredFields()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());

        // test body
        $Fields = $Auth->get_desired_fields();

        // assertions
        $this->assertStringContainsString('desired,fields', $Fields, 'Invalid token URI');
    }

    /**
     * Testing 'dispatch_user_info' method
     */
    public function testDispatchUserInfo()
    {
        // setup
        $Auth = new \Mezon\SocialNetwork\BaseAuth($this->getSettings());
        $UserInfo = [
            'picture' => [
                'data' => [
                    'url' => 'image url'
                ]
            ]
        ];

        // test body
        $UserInfo = $Auth->dispatch_user_info($UserInfo);

        // assertions
        $this->assertIsString($UserInfo['picture'], 'Record was not transformed');
    }

    /**
     * Testing 'auth' method
     */
    public function testAuth()
    {
        // setup
        $Auth = $this->getMockBuilder(\Mezon\SocialNetwork\BaseAuth::class)
            ->setMethods([
            'get_request',
            'request_token'
        ])
            ->setConstructorArgs([
            $this->getSettings()
        ])
            ->getMock();
        $Auth->method('get_request')->willReturn(json_encode([
            'id' => 1,
            'picture' => [
                'data' => [
                    'url' => 'http://'
                ]
            ]
        ]));

        $Auth->method('request_token')->willReturn([
            'access_token' => 'some-token'
        ]);

        // test body
        $Result = $Auth->auth('some-code');

        // assertions
        $this->assertTrue($Result, 'Auth was not performed');
    }
}

?>