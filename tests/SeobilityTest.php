<?php

require_once __DIR__.'/../vendor/autoload.php';

use Bnomei\Seobility;

test('construct', function () {
    $seobility = new Seobility;
    expect($seobility)->toBeInstanceOf(\Bnomei\Seobility::class);
});

test('page view', function () {
    $seobility = new Seobility;

    $data = $seobility->keywordcheck(
        site()->homePage(),
        'atomic design',
        'https://bradfrost.com/blog/post/atomic-web-design/'
    );

    expect($data['score'])->toEqual(75);
});
