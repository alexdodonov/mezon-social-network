# Set of classes for working with social media
[![Build Status](https://travis-ci.org/alexdodonov/mezon-social-network.svg?branch=master)](https://travis-ci.org/alexdodonov/mezon-social-network) [![codecov](https://codecov.io/gh/alexdodonov/mezon-social-network/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-social-network) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-social-network/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-social-network/?branch=master)

## Installation

Just type

```
composer require mezon/social-network
```

## All supported social networks
- Facebook
- VKontakte
- Odnoklassniki

If you need support of the social media wich is not in the list please use [issue creation form](https://github.com/alexdodonov/mezon-social-network/issues)

## Facebook auth code

First of all we need to create [Facebook application](https://developers.facebook.com/apps/)

After that you will be able to use this application for sign in page on your site.

And somewhere in your code you need to create object:

```php
$facebook = new \Mezon\SocialNetwork\Auth\Facebook([
	'client_id' => 'client id from the Facebook application',
	'client_secret' => 'client secret from the Facebook application',
	'redirect_uri' => 'URI of your web application on wih user will be redirected after authorization on Facebook'
]);
```

Then output a button on your page wich will let users to go on the Facebook and use ther facebook account for signing in on your site:

```php
print('<a href="'.$facebook->getLink().'"Sign In</a>a>');
```

After clicking on that link you users will be redirected on the Facebook. There they will confirm that they want to use your application for signing in and grant access to their account's data. And then they will be redurected on the 'redirect_uri' wich you have specified when you created your application on Facebook and the same URL must be used wile $facebook object setup.

```php
// your redirect_uri must process code

if($facebook->auth($_GET['code'])) {
	// authorization was successfull
}
else {
	// an error have occured
}
```

If the method $facebook->auth() have returned `true` then everithing is OK and you can fetch user's data:

```php
var_dump($facebook->userInfo);
// here:
// [
//	'id' => 'user id',
//	'first_name' => 'user first name',
//	'last_name' => 'user last name',
//	'email' => 'user email, but looks like Facebook has forbidden to fetch this info, so dont rely on this field',
//	'picture' => 'user avatar'
// ]
```

# Learn more

More information can be found here:

[Twitter](https://twitter.com/mezonphp)

[dev.to](https://dev.to/alexdodonov)

[Slack](https://join.slack.com/t/mezon-framework/signup?x=x-p1148081653955-1171709616688-1154057706548)