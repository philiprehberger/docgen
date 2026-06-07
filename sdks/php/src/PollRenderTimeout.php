<?php

namespace Docgen\Sdk;

class PollRenderTimeout extends \RuntimeException
{
    public function __construct(public readonly string $renderId, public readonly int $maxWaitMs)
    {
        parent::__construct("pollRender({$renderId}) timed out after {$maxWaitMs}ms");
    }
}
