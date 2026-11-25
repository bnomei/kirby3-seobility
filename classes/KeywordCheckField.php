<?php

namespace Bnomei;

use Kirby\Cms\Page;
use Kirby\Form\Field\TextField;

class KeywordCheckField extends TextField
{
    public function props(): array
    {
        $page = $this->model();
        if (! $page instanceof Page) {
            throw new \InvalidArgumentException('The keywordcheck field requires a page blueprint.');
        }

        $seobility = Seobility::singleton();
        $result = $seobility->keywordcheck($page);

        return [...parent::props(), 'url' => $result['url'], 'score' => $result['score'], 'paid' => ! empty($seobility->option('apikey'))];
    }
}
