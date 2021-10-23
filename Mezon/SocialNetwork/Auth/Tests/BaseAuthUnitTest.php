<?php
namespace Mezon\SocialNetwork\Auth\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BaseAuthUnitTest extends TestCase
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
    public function testConstructor(): void
    {
        // setup and test body
        $Auth = new AdoptedBaseAuth($this->getSettings());

        // assertions
        $this->assertEquals(3, count($Auth->settings), 'Setting were not set');
    }

    /**
     * Testing get_link
     */
    public function testGetLink(): void
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getLink();

        // assertions
        $this->assertStringContainsString(
            'http://oauth-uriclient_id=1&redirect_uri=3&response_type=code',
            $link,
            'Invalid link was generated');
    }

    /**
     * Testing getLink exception
     */
    public function testGetLinkException(): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $auth = new AdoptedBaseAuth([]);

        // test body
        $auth->getLink();
    }

    /**
     * Testing getUserInfoUri
     */
    public function testGetUserInfoUri(): void
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getUserInfoUri();

        // assertions
        $this->assertStringContainsString('://user-info-uri/?', $link, 'Invalid user info URI');
    }

    /**
     * Testing getTokenParams method
     */
    public function testGetTokenParams(): void
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $params = $auth->getTokenParams('123');

        // assertions
        $this->assertEquals(1, $params['client_id'], 'Invalid "client_id"');
        $this->assertEquals(2, $params['client_secret'], 'Invalid "client_secret"');
        $this->assertEquals(3, $params['redirect_uri'], 'Invalid "redirect_uri"');
        $this->assertEquals(123, $params['code'], 'Invalid "code"');
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri(): void
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $link = $auth->getTokenUri();

        // assertions
        $this->assertStringContainsString('://token-uri', $link, 'Invalid token URI');
    }

    /**
     * Testing getDesiredFields
     */
    public function testGetDesiredFields(): void
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
    public function testDispatchUserInfo(): void
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
    public function testAuth(): void
    {
        // setup
        $auth = new AdoptedBaseAuth($this->getSettings());

        // test body
        $result = $auth->auth('some-code');

        // assertions
        $this->assertTrue($result, 'Auth was not performed');
    }
}
