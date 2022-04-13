<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/seobility', [
    'options' => [
        'enabled' => true,
        'apikey' => null,
        'expire' => 0, // has a modified check built in
        'cache' => true,
        'free' => [
            'keywordcheck' => function (string $url, string $keyword) {
                return implode([
                    'https://freetools.seobility.net/de/keywordcheck/check',
                    '?url=' . urlencode($url),
                    '&keyword=' . str_replace([',',' '], ['','+'], $keyword),
                    '&crawltype=1',
                ]);
            },
        ],
        'paid' => [
            // TODO: add paid endpoints
        ],
    ],
    'fields' => [
        'keywordcheck' => [
            'props' => [],
            'computed' => [
                'url' => function () {
                    return option('bnomei.seobility.free.keywordcheck')($this->model, $this->value());
                },
                'keywordcheck' => function () {
                    return $this->model()->keywordcheckScore();
                },
            ],
        ],
    ],
    'pageMethods' => [
        'keywordcheckScore' => function () {
            return \Bnomei\Seobility::singleton()->keywordcheck($this, $this->keywordcheck()->value())['score'];
        },
    ],
]);
