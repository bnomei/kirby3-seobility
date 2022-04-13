# Kirby 3 Seobility

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-seobility?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-seobility?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-seobility)](https://travis-ci.com/bnomei/kirby3-seobility)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-seobility)](https://coveralls.io/github/bnomei/kirby3-seobility) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-seobility)](https://codeclimate.com/github/bnomei/kirby3-seobility) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Kirby 3 Plugin to use free and paid API of [Seobility.net](https://www.seobility.net/)

## Commercial Usage

> <br>
> <b>Support open source!</b><br><br>
> This plugin is free but if you use it in a commercial project please consider to sponsor me or make a donation.<br>
> If my work helped you to make some cash it seems fair to me that I might get a little reward as well, right?<br><br>
> Be kind. Share a little. Thanks.<br><br>
> &dash; Bruno<br>
> &nbsp; 

| M | O | N | E | Y |
|---|----|---|---|---|
| [Github sponsor](https://github.com/sponsors/bnomei) | [Patreon](https://patreon.com/bnomei) | [Buy Me a Coffee](https://buymeacoff.ee/bnomei) | [Paypal dontation](https://www.paypal.me/bnomei/15) | [Hire me](mailto:b@bnomei.com?subject=Kirby) |

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-seobility/archive/master.zip) as folder `site/plugins/kirby3-seobility` or
- `git submodule add https://github.com/bnomei/kirby3-seobility.git site/plugins/kirby3-seobility` or
- `composer require bnomei/kirby3-seobility`

## Roadmap

- [x] add features of free api
- [ ] add features of paid api

## Usage free API

Add the field to your blueprint.

**site/blueprints/default.yml**
```yaml
fields:
  keywordcheck: # the field id must be exactly like this
    label: Seobility.net Keywordcheck
    type: keywordcheck
```

Enter keywords(s) in the panel. Save and get a score.

![keywordcheck](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot.png)

You can also read the score with a pagemethod if you need it in you business logic.

**any template**
```php
echo $page->keywordcheckScore();
```

## How it works

The plugin will query the free or paid API and cache the results until the content page is modified or cache expires (see settings below).

> ⚠️ EVERY time you press the save button in the panel for a page with this field a request to the API will be made. This might delay saving by a second or two.

## Localhost = No Score

The plugin will not query the API on localhost since the API would not be able to read the HTML content of your page.

## No cache when debugging

When Kirbys global debug config is set to true the complete plugin cache will be flushed BUT caches will be created. This will make you live easier – trust me.

## Setup paid API

You can set the apikey in the config if you want to use features from the [paid api](https://www.seobility.net/static/api/documentation.html).

**site/config/config.php**
```php
return [
    // other config settings ...
    'bnomei.seobility.apikey' => 'YOUR-KEY-HERE',
];
```

You can also set a callback if you use the [dotenv Plugin](https://github.com/bnomei/kirby3-dotenv).

**site/config/config.php**
```php
return [
    // other config settings ...
    'bnomei.seobility.apikey' => function() { return env('SEOBILITY_APIKEY'); },
];
```

## Settings

| bnomei.seobility. | Default | Description                                |            
|-------------------|---------|--------------------------------------------|
| enabled           | `true`  | but disabled on localhost by default       |
| expire            | `0`     | will expire on modified or after n-minutes |
| apikey            | `null`  | string or callback                         |

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-seobility/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
