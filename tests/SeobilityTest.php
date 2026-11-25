<?php

require_once __DIR__.'/../vendor/autoload.php';

use Bnomei\Seobility;

test('construct', function () {
    $seobility = new Seobility;
    expect($seobility)->toBeInstanceOf(\Bnomei\Seobility::class);
});

test('keyword check reads the score from the paid endpoint', function () {
    $server = proc_open(
        [PHP_BINARY, '-S', '127.0.0.1:0', '-t', __DIR__.'/fixtures'],
        [2 => ['pipe', 'w']],
        $pipes
    );

    try {
        stream_set_timeout($pipes[2], 5);
        $started = fgets($pipes[2]);
        expect(preg_match('~http://127\.0\.0\.1:\d+~', (string) $started, $address))->toBe(1);

        $kirby = new Kirby(['roots' => ['index' => __DIR__], 'options' => [
            'bnomei.seobility.cache' => ['type' => 'memory'],
            'bnomei.seobility.paid.keywordcheck' => function ($url, $keyword) use ($address) {
                expect($url)->toBe('https://example.test/article');
                expect($keyword)->toBe('atomic design');

                return $address[0].'/keywordcheck.json';
            },
        ]]);
        $seobility = new Seobility(['enabled' => 'force', 'apikey' => 'test']);
        $data = $seobility->keywordcheck($kirby->site()->homePage(), 'atomic design', 'https://example.test/article');

        expect($data['score'])->toEqual(75);
    } finally {
        proc_terminate($server);
        fclose($pipes[2]);
        proc_close($server);
    }
});
