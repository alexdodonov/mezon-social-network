<?php
namespace Mezon\SocialNetwork\Auth\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\SocialNetwork\Auth\Vkontakte;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class VKAuthUnitTest extends TestCase
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
    public function testGetUerInfoUri(): void
    {
        // setup
        $auth = new Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/api.vk.com/method/users.get?v=', $auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri(): void
    {
        // setup
        $auth = new Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertStringContainsString('/oauth.vk.com/access_token?v=', $auth->getTokenUri());
    }

    /**
     * Testing getDesiredFields
     */
    public function testGetDesiredFields(): void
    {
        // setup
        $auth = new Vkontakte($this->getSettings());

        // test body
        $fields = $auth->getDesiredFields();

        // assertions
        $this->assertStringContainsString('id', $fields);
        $this->assertStringContainsString('first_name', $fields);
        $this->assertStringContainsString('last_name', $fields);
        $this->assertStringContainsString('email', $fields);
        $this->assertStringContainsString('photo_100', $fields);
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo(): void
    {
        // setup
        $auth = new Vkontakte($this->getSettings());

        // test body
        $result = $auth->dispatchUserInfo(
            [
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
        $this->assertArrayHasKey('id', $result, 'id was not found');
        $this->assertArrayHasKey('first_name', $result, 'first_name was not found');
        $this->assertArrayHasKey('last_name', $result, 'last_name was not found');
        $this->assertArrayHasKey('picture', $result, 'picture was not found');
        $this->assertArrayHasKey('email', $result, 'email was not found');
    }

    /**
     * Testing method getOauthUri
     */
    public function testGetOauthUri(): void
    {
        // setup
        $auth = new Vkontakte($this->getSettings());

        // test body and assertions
        $this->assertEquals('https://oauth.vk.com/authorize?v=5.0&', $auth->getOauthUri());
    }
}
