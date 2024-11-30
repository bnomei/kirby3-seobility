# Kirby Seobility

[![Kirby 5](https://flat.badgen.net/badge/Kirby/5?color=ECC748)](https://getkirby.com)
![PHP 8.2](https://flat.badgen.net/badge/PHP/8.2?color=4E5B93&icon=php&label)
![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-seobility?color=ae81ff&icon=github&label)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-seobility?color=272822&icon=github&label)
[![Coverage](https://flat.badgen.net/codeclimate/coverage/bnomei/kirby3-seobility?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-seobility)
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-seobility?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-seobility/issues)
[![Discord](https://flat.badgen.net/badge/discord/bnomei?color=7289da&icon=discord&label)](https://discordapp.com/users/bnomei)
[![Buymecoffee](https://flat.badgen.net/badge/icon/donate?icon=buymeacoffee&color=FF813F&label)](https://www.buymeacoffee.com/bnomei)

Kirby Plugin to use [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin)
- keyword check (scrapper, paid api)
- real time SERP ranking (paid api)
- term suggestion (paid api)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-seobility/archive/master.zip) as folder `site/plugins/kirby3-seobility` or
- `git submodule add https://github.com/bnomei/kirby3-seobility.git site/plugins/kirby3-seobility` or
- `composer require bnomei/kirby3-seobility`

## Requirements 

### robots.txt

You need a `robots.txt` file for the checks to work on your production server. Either you create a custom `robots.txt`-file or use my [Robots.txt plugin](https://github.com/bnomei/kirby3-robots-txt).
Make sure the [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin) bot can crawl the website by setting your global debug config to `false` or by adding the following to your `robots.txt`:

```plaintext
User-agent: *
Disallow: /kirby/
Disallow: /site/
Disallow: /cdn-cgi/
Allow: /media/
```

### Localhost = No Score

The plugin will not query the API on localhost since the API would not be able to read the HTML content of your page.

## Usage

### Keyword check (scrapper, paid)
Add the field `keywordcheck` to your blueprints.

**site/blueprints/default.yml**
```yaml
fields:
  keywordcheck: # the field id must be exactly like this
    label: Seobility.net Keywordcheck
    type: keywordcheck
```

Enter keywords(s) in the Panel. Save and get a score. Clicking on the score will take you to new browser tab with the full report.

![keywordcheck](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot-keywordcheck.png)

You can also read the score with a PageMethod if you need it in you business logic.

**site/templates/default.php**
```php
echo $page->keywordcheckScore();
```

To show the score of the `keywordcheck` field the plugin will scrape the web based tools of [Seobility.net](https://www.seobility.net/?ref=kirby3-seobility-plugin) or query your paid API account and cache the results until the content page is modified or cache expires (see settings below).

> [!TIP]
> EVERY time you press the save button in the Panel for a page with this field a request to the API will be made. This might delay saving by a second or two. The paid API is a tiny bit faster.

### Real-time SERP Ranking (paid api)

This field is a button to trigger a real-time, synchronous (direct) API call. The average response time is **up to 30 seconds** and it will return the rank, title and description as listed on the specified search engine (see config setting `bnomei.seobility.searchengine`).

> [!NOTE]
> You need to have a `keywordcheck` field on the same blueprint and at least one keyword set to get a SERP ranking.

**site/blueprints/default.yml**
```yaml
fields:
  ranking:
    headline: Seobility.net SERP Ranking
    label: Fetch Rank
    progress: Fetching Rank...
    # notranked: Page is not ranked.
    type: ranking
```

![ranking](https://raw.githubusercontent.com/bnomei/kirby3-seobility/master/screenshot-ranking.png)

### Term Suggestion (paid api)

This field is a button to trigger a term suggestion (more, less, ok) for the specified search engine (see settings).

> [!NOTE]
> You need to have a `keywordcheck` field on the same blueprint and at least one keyword set to get further term suggestions.

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

## Paid API

You can set the API-key in the config if you want to use features from the [paid api](https://www.seobility.net/static/api/documentation.html).

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
    'bnomei.seobility.apikey' => function() { 
        return env('SEOBILITY_APIKEY'); 
    },
];
```

### Cache

When Kirby's **global** debug config is set to `true` the complete plugin cache will be flushed but caches will still be created.

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
