<?php

class AdoptedBaseAuth extends \Mezon\SocialNetwork\BaseAuth
{

    public function getUserInfoUri(string $token = ''): string
    {
        return 'http://user-info-uri/?' . $token;
    }

    public function getTokenUri(): string
    {
        return 'http://token-uri';
    }

    public function getOauthUri(): string
    {
        return 'http://oauth-uri';
    }
}

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
        $Auth = new AdoptedBaseAuth($this->getSettings());

        // assertions
        $this->assertEquals(3, count($Auth->settings), 'Setting were not set');
    }

    /**
     * Testing get_link
     */
    public function testGetLink()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getLink();

        // assertions
        $this->assertStringContainsString('http://oauth-uriclient_id=1&redirect_uri=3&response_type=code', $link, 'Invalid link was generated');
    }

    /**
     * Testing get_link exception
     */
    public function testGetLinkException()
    {
        // setup
        $auth = new AdoptedBaseAuth([]);

        try {
            // test body and assertions
            $auth->getLink();
            $this->fail('Exception must be thrown');
        } catch (Exception $e) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * Testing getUserInfoUri
     */
    public function testGetUserInfoUri()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getUserInfoUri();

        // assertions
        $this->assertStringContainsString('://user-info-uri/?', $link, 'Invalid user info URI');
    }

    /**
     * Testing get_token_params method
     */
    public function testGetTokenParams()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $params = $auth->getTokenParams(123);

        // assertions
        $this->assertEquals(1, $params['client_id'], 'Invalid "client_id"');
        $this->assertEquals(2, $params['client_secret'], 'Invalid "client_secret"');
        $this->assertEquals(3, $params['redirect_uri'], 'Invalid "redirect_uri"');
        $this->assertEquals(123, $params['code'], 'Invalid "code"');
    }

    /**
     * Testing get_token_uri
     */
    public function testGetTokenUri()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getTokenUri();

        // assertions
        $this->assertStringContainsString('://token-uri', $link, 'Invalid token URI');
    }

    /**
     * Testing get_desired_fields
     */
    public function testGetDesiredFields()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $fields = $auth->getDesiredFields();

        // assertions
        $this->assertStringContainsString('desired,fields', $fields, 'Invalid token URI');
    }

    /**
     * Testing 'dispatchUserInfo' method
     */
    public function testDispatchUserInfo()
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());
        $userInfo = [
            'picture' => [
                'data' => [
                    'url' => 'image url'
                ]
            ]
        ];

        // test body
        $userInfo = $auth->dispatchUserInfo($userInfo);

        // assertions
        $this->assertIsString($userInfo['picture'], 'Record was not transformed');
    }

    /**
     * Testing 'auth' method
     */
    public function testAuth()
    {
        // setup
        $auth = $this->getMockBuilder(AdoptedBaseAuth::class)
            ->setMethods([
            'getRequest',
            'requestToken'
        ])
            ->setConstructorArgs([
            $this->getSettings()
        ])
            ->getMock();
        $auth->method('getRequest')->willReturn(json_encode([
            'id' => 1,
            'picture' => [
                'data' => [
                    'url' => 'http://'
                ]
            ]
        ]));

        $auth->method('requestToken')->willReturn([
            'access_token' => 'some-token'
        ]);

        // test body
        $result = $auth->auth('some-code');

        // assertions
        $this->assertTrue($result, 'Auth was not performed');
    }
}
