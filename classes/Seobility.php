<?php

declare(strict_types=1);

namespace Bnomei;

use Exception;
use Kirby\Http\Remote;
use Kirby\Toolkit\A;

final class Seobility
{
    /** @var array $options */
    private $options;

    public function __construct(array $options = [])
    {
        $defaults = [
            'debug' => option('debug'),
            'enabled' => option('bnomei.seobility.enabled'),
            'expire' => intval(option('bnomei.seobility.expire')),
            'apikey' => option('bnomei.seobility.apikey'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options as $key => $call) {
            if (is_callable($call) && in_array($key, ['apikey'])) {
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

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public function option(?string $key = null)
    {
        if ($key) {
            return A::get($this->options, $key);
        }
        return $this->options;
    }

    public function keywordcheck(\Kirby\Cms\Page $page, ?string $keyword = null, ?string $url = null): array
    {
        $keyword = $keyword ?? $page->keywordcheck()->value();
        $key = md5($page->url() . $keyword);
        $data = kirby()->cache('bnomei.seobility')->get($key);
        if (!$data || intval(A::get($data, 'modified')) < $page->modified()) {
            $score = 0;
            $check = $url ?? $page->url();
            if ($page->isDraft() || empty($keyword)) {
                $check = false;
            }
            if ($check && $this->option('apikey')) {
                // TODO: paid endpoint
            } elseif ($check) {
                $url = option('bnomei.seobility.free.keywordcheck')($check, $keyword);
                $remote = Remote::get($url);
                if ($remote->code() == 200) {
                    preg_match_all(
                        '/"data":(.*?),/',
                        (string) $remote->content(),
                        $matches
                    );
                    if ($matches && count($matches[1])) {
                        $score = intval($matches[1][0]);
                    }
                }
            }

            $data = [
                'modified' => $page->modified(),
                'score' => $score,
                'url' => $url,
            ];
            kirby()->cache('bnomei.seobility')->set($key, $data, $this->option('expire'));
        }

        return $data;
    }

    /** @var Seobility */
    private static $singleton;

    /**
     * @param array $options
     * @return Seobility
     */
    public static function singleton(array $options = [])
    {
        if (!self::$singleton) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
