# Kirby 3 Seobility

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-seobility?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-seobility?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-seobility)](https://travis-ci.com/bnomei/kirby3-seobility)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-seobility)](https://coveralls.io/github/bnomei/kirby3-seobility) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-seobility)](https://codeclimate.com/github/bnomei/kirby3-seobility) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Kirby 3 Plugin to use [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin)

## Commercial Usage

> <br>
> <b>Support Seobility.net</b><br><br>
> Unless you enter a paid API key this plugin will scrape the web based tools of Seobility.net. They know about this, did send me a really nice email and decided for now not to block the scrapping. Go signup for an account with API access to support them! The api is also a tiny bit faster than the scrapper.
> <br>
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

### Scrapper
- [x] keyword check (scrapper for web based tool, not a free api)

### Paid API
- [x] keyword check
- [x] real time SERP ranking
- [ ] add more features of paid api

## Usage

### Keyword check (scrapper, paid)
Add the field to your blueprint.

**site/blueprints/default.yml**
```yaml
fields:
  keywordcheck: # the field id must be exactly like this
    label: Seobility.net Keywordcheck
    type: keywordcheck
```

Enter keywords(s) in the panel. Save and get a score. Clicking on the score will take you to new browser tab with the full report.

![keywordcheck](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot-keywordcheck.png)

You can also read the score with a pagemethod if you need it in you business logic.

**any template**
```php
echo $page->keywordcheckScore();
```

### Real time SERP Ranking (paid)

This field is a button to trigger a real time, synchronous (direct) API. The average response time is up to 30 seconds and it will return the rank, title and description as listed on the specified search engine (see settings).

**site/blueprints/default.yml**
```yaml
fields:
  serpranking:
    headline: Seobility.net SERP Ranking
    label: Fetch Rank
    progress: Fetching Rank...
    # notranked: Page is not ranked.
    type: ranking
```

![ranking](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot-ranking.png)

### Term Suggestion (paid)

This field is a button to trigger a term suggestion (more, less, ok) for the specified search engine (see settings).

**site/blueprints/default.yml**
```yaml
fields:
  termsuggestion:
    headline: Seobility.net Term Suggestion
    label: Fetch Term Suggestions
    progress: Fetching Term Suggestions...
    type: termsuggestion
```

![termsuggestion](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot-termsuggestion.png)

## Robots.txt

If you have a custom `robots.txt`-file or use my plugin make sure the [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin) bot can crawl the website. My [Robots.txt plugin](https://github.com/bnomei/kirby3-robots-txt) must be in **non debug mode**.

In a custom `robots.txt`-file add something like this:
```
User-Agent: seobility
Allow: /
```

## How it works

The plugin will scrape the web based tools of [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin) or query your paid API account and cache the results until the content page is modified or cache expires (see settings below).

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

| bnomei.seobility. | Default      | Description                                |            
|-------------------|--------------|--------------------------------------------|
| enabled           | `true`       | but disabled on localhost by default       |
| expire            | `0`          | will expire on modified or after n-minutes |
| apikey            | `null`       | string or callback                         |
| searchengine      | `google.com` | the target searchengine domain             |

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-seobility/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
