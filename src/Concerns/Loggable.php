<?php

namespace dnj\UserLogger\Concerns;

use dnj\UserLogger\ModelUtils;

trait Loggable
{
    public function changesForLog(): ?array
    {
        return ModelUtils::changesForLog($this);
    }
}
