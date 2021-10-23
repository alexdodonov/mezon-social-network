<?php
namespace Mezon\SocialNetwork\Auth\Tests;

use Mezon\SocialNetwork\BaseAuth;

class AdoptedBaseAuth extends BaseAuth
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

    protected function getRequest(string $url): string
    {
        return json_encode([
            'id' => 1,
            'picture' => [
                'data' => [
                    'url' => 'http://'
                ]
            ]
        ]);
    }

    public function requestToken(array $params): array
    {
        return [
            'access_token' => 'some-token'
        ];
    }
}
