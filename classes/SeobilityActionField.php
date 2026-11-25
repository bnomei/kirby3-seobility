<?php

namespace Bnomei;

use Kirby\Form\Field\DisplayField;
use Kirby\Reflection\Attributes\Prop;

class SeobilityActionField extends DisplayField
{
    #[Prop]
    protected ?string $headline = null;

    #[Prop]
    protected ?string $progress = null;

    #[Prop]
    protected string $notranked = 'Page is not ranked.';
}
