<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/seobility', [
    'options' => [
        'enabled' => true,
        'apikey' => null,
        'expire' => 0, // has a modified check built in
        'cache' => true,
        'searchengine' => 'google.com',
        'free' => [
            'keywordcheck' => function (string $url, ?string $keyword = null, ?string $lang = null) {
                $lang = $lang ?? 'en';
                if (kirby()->languages()->count() > 0 && kirby()->language()->code() === 'de') {
                    $lang = 'de';
                }
                return implode([
                    'https://freetools.seobility.net/'.$lang.'/keywordcheck/check',
                    '?url=' . urlencode($url),
                    '&keyword=' . str_replace([',',' '], ['','+'], $keyword ?? ''),
                    '&crawltype=1',
                    '&ref=kirby3-seobility-plugin',
                ]);
            },
        ],
        'paid' => [
            // TODO: Keyword, term suggestion and Ranking
            'keywordcheck' => function (string $url, ?string $keyword = null, ?string $lang = null) {
                // https://www.seobility.net/static/api/documentation.html#keywordcheck
                return implode([
                    'https://api.seobility.net/en/resellerapi/keywordcheck',
                    '?url=' . urlencode($url),
                    '&keyword=' . str_replace([',',' '], ['','+'], $keyword ?? ''),
                    '&apikey=' . \Bnomei\Seobility::singleton()->option('apikey'),
                    '&ref=kirby3-seobility-plugin',
                ]);
            },
            'ranking' => function (string $url, ?string $keyword = null, ?string $lang = null) {
                // https://www.seobility.net/static/api/documentation.html#ranking
                return implode([
                    'https://api.seobility.net/en/resellerapi/ranking',
                    '?url=' . urlencode($url),
                    '&keyword=' . str_replace([',',' '], ['','+'], $keyword ?? ''),
                    '&searchengine=' . \Bnomei\Seobility::singleton()->option('searchengine'),
                    '&apikey=' . \Bnomei\Seobility::singleton()->option('apikey'),
                    '&ref=kirby3-seobility-plugin',
                ]);
            },
        ],
    ],
    'fields' => [
        'keywordcheck' => [
            'props' => [],
            'computed' => [
                'url' => function () {
                    return \Bnomei\Seobility::singleton()->keywordcheck($this->model)['url'];
                },
                'score' => function () {
                    return $this->model()->keywordcheckScore();
                },
                'paid' => function () {
                    return !empty(\Bnomei\Seobility::singleton()->option('apikey'));
                },
            ],
        ],
        'ranking' => [
            'props' => [],
            'computed' => [],
        ],
    ],
    'pageMethods' => [
        'keywordcheckScore' => function () {
            return \Bnomei\Seobility::singleton()->keywordcheck($this)['score'];
        },
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'seobility/(:any)',
                'action'  => function (string $endpoint) {
                    $id = urldecode(get('id'));
                    $lang = get('lang');
                    if ($lang == 'false') {
                        $lang = null;
                    }
                    $id = explode('?', ltrim(str_replace(['/pages/','/_drafts/','+',' '], ['/','/','/','/'], $id), '/'))[0];
                    $page = page($id);
                    return $page ? \Bnomei\Seobility::singleton()->{$endpoint}(
                        $page,
                        null, // auto
                        $page->url($lang)
                    ) : [];
                },
            ],
        ],
    ],
]);
