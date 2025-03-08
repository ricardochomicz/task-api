<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected function beginTransaction()
    {
        DB::beginTransaction();
    }

    protected function commitTransaction()
    {
        DB::commit();
    }

    protected function rollBackTransaction()
    {
        DB::rollBack();
    }

    protected function executeTransaction(callable $callback)
    {
        try {
            $this->beginTransaction();
            $result = $callback();
            $this->commitTransaction();
            return $result;
        } catch (\Throwable $e) {
            $this->rollBackTransaction();
            throw $e;
        }
    }
}
