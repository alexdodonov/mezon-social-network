<?php
namespace Mezon\SocialNetwork\Auth\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\SocialNetwork\Auth\Odnoklassniki;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class OKUnitTest extends TestCase
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
    public function testGetUserInfoUri(): void
    {
        // setup
        $auth = new Odnoklassniki($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/api.odnoklassniki.ru/fb.do?application_key=', $auth->getUserInfoUri());
        $this->assertStringContainsString('?application_key=4', $auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri(): void
    {
        // setup
        $auth = new Odnoklassniki($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString(
            '/api.odnoklassniki.ru/oauth/token.do?grant_type=authorization_code&',
            $auth->getTokenUri());
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo(): void
    {
        // setup
        $auth = new Odnoklassniki($this->getSettings());

        // test body
        $result = $auth->dispatchUserInfo(
            [
                'uid' => '',
                'first_name' => '',
                'last_name' => '',
                'pic190x190' => '',
                'email' => ''
            ]);

        // assertions
        $this->assertArrayHasKey('id', $result, 'id was not found');
        $this->assertArrayHasKey('first_name', $result, 'first_name was not found');
        $this->assertArrayHasKey('last_name', $result, 'last_name was not found');
        $this->assertArrayHasKey('picture', $result, 'picture was not found');
        $this->assertArrayHasKey('email', $result, 'email was not found');
    }

    /**
     * Testing getTokenParams method
     */
    public function testGetTokenParams(): void
    {
        // setup
        $auth = new Odnoklassniki($this->getSettings());

        // test body
        $params = $auth->getTokenParams('123');

        // assertions
        $this->assertEquals(1, $params['client_id'], 'Invalid "client_id"');
        $this->assertEquals(2, $params['client_secret'], 'Invalid "client_secret"');
        $this->assertEquals(3, $params['redirect_uri'], 'Invalid "redirect_uri"');
        $this->assertEquals('authorization_code', $params['grant_type'], 'Invalid "grant_type"');
        $this->assertEquals(123, $params['code'], 'Invalid "code"');
    }

    /**
     * Testing method getOauthUri
     */
    public function testGetOauthUri(): void
    {
        // setup
        $auth = new Odnoklassniki($this->getSettings());

        // test body
        $result = $auth->getOauthUri();

        // assertions
        $this->assertStringContainsString('https://connect.ok.ru/', $result);
        $this->assertStringContainsString('?scope=VALUABLE_ACCESS;PHOTO_CONTENT&', $result);
    }
}
