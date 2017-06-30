<?php

namespace App\Services;

use App\Contracts\TestContract;

class TestService implements TestContract
{
    public function wtf($controller)
    {
        dd('Call Me From TestServiceProvider In What The Fuck '.$controller);
    }
}
