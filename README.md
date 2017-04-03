# BotMan Tinker

Gives your Laravel chatbot the ability to try your chatbot in your local terminal.

## Installation

Run `composer require mpociot/botman-tinker` to install the composer dependencies.

Then in your `config/app.php` add

```php
Mpociot\BotManTinker\BotManTinkerServiceProvider::class,
```

to the `providers` array.

## Usage

You now have a new Artisan command that helps you to test and develop your chatbot locally:

```php
php artisan botman:tinker
```

## About BotMan

BotMan is a framework agnostic PHP library that is designed to simplify the task of developing innovative bots for multiple messaging platforms, including [Slack](http://slack.com), [Telegram](http://telegram.me), [Microsoft Bot Framework](https://dev.botframework.com/), [Nexmo](https://nexmo.com), [HipChat](http://hipchat.com), [Facebook Messenger](http://messenger.com) and [WeChat](http://web.wechat.com).

```php
$botman->hears('I want cross-platform bots with PHP!', function (BotMan $bot) {
    $bot->reply('Look no further!');
});
```

## Documentation

You can find the BotMan documentation at [http://botman.io](http://botman.io).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover a security vulnerability within BotMan, please send an e-mail to Marcel Pociot at m.pociot@gmail.com. All security vulnerabilities will be promptly addressed.

## License

BotMan Tinker is free software distributed under the terms of the MIT license.
