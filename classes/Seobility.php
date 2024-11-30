<?php

declare(strict_types=1);

namespace Bnomei;

use Closure;
use Exception;
use Kirby\Cms\Page;
use Kirby\Data\Json;
use Kirby\Http\Remote;
use Kirby\Toolkit\A;

final class Seobility
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'debug' => option('debug'),
            'version' => Json::read(__DIR__.'/../composer.json')['version'],
            'enabled' => option('bnomei.seobility.enabled'),
            'expire' => intval(option('bnomei.seobility.expire')),
            'apikey' => option('bnomei.seobility.apikey'),
            'searchengine' => option('bnomei.seobility.searchengine'),
        ], $options);

        foreach ($this->options as $key => $call) {
            if ($call instanceof Closure && in_array($key, ['apikey', 'searchengine'])) {
                $this->options[$key] = $call();
            }
        }

        if (kirby()->system()->isLocal()) {
            $this->options['enabled'] = false;
        }

        if ($this->option('debug')) {
            try {
                kirby()->cache('bnomei.seobility')->flush();
            } catch (Exception $e) {
                //
            }
        }
    }

    public function option(?string $key = null): mixed
    {
        if ($key) {
            return A::get($this->options, $key);
        }

        return $this->options;
    }

    public function api(string $url): ?Remote
    {
        $remote = Remote::get($url, [
            'headers' => [
                'X-Kirby3-Seobility-Plugin' => $this->option('version'),
            ],
        ]);
        if ($remote->code() == 200) {
            return $remote;
        } elseif ($remote->code() == 451) {
            kirby()->cache('bnomei.seobility')->set('Error', 60 * 24 * 7);  // minutes
        } elseif ($remote->code() == 429) {
            kirby()->cache('bnomei.seobility')->set('Error', 60);  // minutes
        } else {
            kirby()->cache('bnomei.seobility')->set('Error', 1); // minutes
        }

        return null;
    }

    public function keywordcheck(Page $page, ?string $keyword = null, ?string $url = null): array
    {
        $check = $url ?? $page->url();
        $keyword = $keyword ?? $page->keywordcheck()->value(); // @phpstan-ignore-line

        $default = [
            'modified' => $page->modified(),
            'score' => 0,
            // 'keyword' => $keyword,
            'url' => option('bnomei.seobility.free.keywordcheck')($check, $keyword), // @phpstan-ignore-line
        ];

        if (! $this->option('enabled') || $page->isDraft() ||
            empty($keyword) || kirby()->cache('bnomei.seobility')->get('Error')) {
            return $default;
        }

        $key = md5('keywordcheck'.$page->url().$keyword.$this->option('apikey'));

        $data = kirby()->cache('bnomei.seobility')->get($key);
        if (! $data || intval(A::get($data, 'modified')) < $page->modified()) {
            $data = $default;
            if ($this->option('apikey') && $remote = $this->api(option('bnomei.seobility.paid.keywordcheck')($check, $keyword))) { // @phpstan-ignore-line
                $data['score'] = intval(A::get($remote->json(), 'score', 0));
            } elseif ($remote = $this->api(option('bnomei.seobility.free.keywordcheck')($check, $keyword))) { // @phpstan-ignore-line
                // scrapper
                preg_match_all(
                    '/"data":(.*?),/',
                    (string) $remote->content(),
                    $matches
                );
                if (count($matches[1])) {
                    $data['score'] = intval($matches[1][0]);
                }
            }

            kirby()->cache('bnomei.seobility')->set($key, $data, intval($this->option('expire')));
        }

        return $data;
    }

    public function ranking(Page $page, ?string $keyword = null, ?string $url = null): array
    {
        $check = $url ?? $page->url();
        $keyword = $keyword ?? $page->keywordcheck()->value(); // @phpstan-ignore-line

        $default = [
            'modified' => $page->modified(),
            'rank' => 0,
            // 'keyword' => $keyword,
        ];

        if (! $this->option('enabled') || empty($this->option('apikey')) ||
            $page->isDraft() || empty($keyword) ||
            kirby()->cache('bnomei.seobility')->get('Error')) {
            return $default;
        }

        $key = md5('ranking'.$page->url().$keyword.$this->option('apikey'));

        $data = kirby()->cache('bnomei.seobility')->get($key);
        if (! $data || intval(A::get($data, 'modified')) < $page->modified()) {
            $data = $default;
            if ($remote = $this->api(option('bnomei.seobility.paid.ranking')($check, $keyword))) { // @phpstan-ignore-line
                if ($targeturlresult = A::get($remote->json(), 'targeturlresult')) {
                    $data['rank'] = intval(A::get($targeturlresult, 'rank'));
                    $data['title'] = A::get($targeturlresult, 'title');
                    $data['description'] = A::get($targeturlresult, 'description');
                }
            }

            kirby()->cache('bnomei.seobility')->set($key, $data, intval($this->option('expire')));
        }

        return $data;
    }

    public function termsuggestion(Page $page, ?string $keyword = null, ?string $url = null): array
    {
        $check = $url ?? $page->url();
        $keyword = $keyword ?? $page->keywordcheck()->value(); // @phpstan-ignore-line

        $default = [
            'modified' => $page->modified(),
            'more' => '',
            'less' => '',
            'ok' => '',
            // 'keyword' => $keyword,
        ];

        if (! $this->option('enabled') || empty($this->option('apikey')) ||
            $page->isDraft() || empty($keyword) ||
            kirby()->cache('bnomei.seobility')->get('Error')) {
            return $default;
        }

        $key = md5('termsuggestion'.$page->url().$keyword.$this->option('apikey'));

        $data = kirby()->cache('bnomei.seobility')->get($key);
        if (! $data || intval(A::get($data, 'modified')) < $page->modified()) {
            $data = $default;
            if ($remote = $this->api(option('bnomei.seobility.paid.termsuggestion')($check, $keyword))) { // @phpstan-ignore-line
                if ($termsuggestions = A::get($remote->json(), 'termsuggestions')) {
                    $data['more'] = A::get($termsuggestions, 'more');
                    $data['less'] = A::get($termsuggestions, 'less');
                    $data['ok'] = A::get($termsuggestions, 'ok');
                    // $data['text'] = A::get($termsuggestions, 'text');
                }
            }

            kirby()->cache('bnomei.seobility')->set($key, $data, intval($this->option('expire')));
        }

        return $data;
    }

    private static ?self $singleton = null;

    public static function singleton(array $options = []): self
    {
        if (self::$singleton === null) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
