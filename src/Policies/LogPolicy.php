<?php

namespace dnj\UserLogger\Policies;

use dnj\AAA\Policy;
use dnj\UserLogger\Contracts\ILog;

class LogPolicy extends Policy
{
    public function getModel(): string
    {
        return ILog::class;
    }
}
