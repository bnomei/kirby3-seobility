<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/seobility', [
    'options' => [
        'enabled' => true,
        'apikey' => null,
        'expire' => 0, // has a modified check built in
        'cache' => true,
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
                return implode([
                    'https://api.seobility.net/en/resellerapi/keywordcheck',
                    '?url=' . urlencode($url),
                    '&keyword=' . str_replace([',',' '], ['','+'], $keyword ?? ''),
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
            ],
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
                'pattern' => 'seobility/keywordcheck',
                'action'  => function () {
                    $id = urldecode(get('id'));
                    $lang = get('lang');
                    if ($lang == 'false') {
                        $lang = null;
                    }
                    $id = explode('?', ltrim(str_replace(['/pages/','/_drafts/','+',' '], ['/','/','/','/'], $id), '/'))[0];
                    $page = page($id);
                    return $page ? \Bnomei\Seobility::singleton()->keywordcheck(
                        $page,
                        null, // auto
                        $page->url($lang)
                    ) : [];
                },
            ],
        ],
    ],
]);
