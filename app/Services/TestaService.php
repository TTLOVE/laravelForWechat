<?php

namespace App\Services;

use App\Contracts\TestaContract;

class TestaService implements TestaContract
{
    public function wtf($controller)
    {
        dd('1111 Call Me From TestServiceProvider In What The Fuck '.$controller);
    }
}
