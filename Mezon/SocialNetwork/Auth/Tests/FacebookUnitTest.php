<?php
namespace Mezon\SocialNetwork\Auth\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\SocialNetwork\Auth\Facebook;

class FacebookUnitTest extends TestCase
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
        // setupp
        $auth = new Facebook($this->getSettings());

        // test body and assertionss
        $this->assertStringContainsString('/graph.facebook.com/me?', $auth->getUserInfoUri());
    }

    /**
     * Testing getTokenUri
     */
    public function testGetTokenUri()
    {
        // setupp
        $auth = new Facebook($this->getSettings());

        // test body and assertionss
        $this->assertStringContainsString('/graph.facebook.com/oauth/access_token?', $auth->getTokenUri());
    }

    /**
     * Testing getDesiredFields
     */
    public function testGetDesiredFields()
    {
        // setupp
        $auth = new Facebook($this->getSettings());

        // test body and assertionss
        $this->assertStringContainsString(
            'id,first_name,last_name,email,picture.width(120).height(120)',
            $auth->getDesiredFields());
    }

    /**
     * Testing dispatchUserInfo
     */
    public function testDispatchUserInfo()
    {
        // setupp
        $auth = new Facebook($this->getSettings());

        // test bodyy
        $result = $auth->dispatchUserInfo(
            [
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

        // assertionss
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
        $auth = new Facebook($this->getSettings());
        
        // test body
        $result = $auth->getOauthUri();
        
        // assertions
        $this->assertEquals('https://www.facebook.com/dialog/oauth?', $result);
    }
}
