<?php

namespace dnj\UserLogger;

trait ModelHelpers
{
    protected function getUserModel(): ?string
    {
        return config('user-logger.user_model');
    }

    protected function getUserTable(): ?string
    {
        $userModel = $this->getUserModel();

        $userTable = null;
        if ($userModel) {
            $userTable = (new $userModel())->getTable();
        }

        return $userTable;
    }
}
