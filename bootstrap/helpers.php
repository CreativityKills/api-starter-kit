<?php

declare(strict_types=1);

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('currentUserOrFail')) {
    function currentUserOrFail(int $code = Response::HTTP_NOT_FOUND): User
    {
        return request()->user() ?? abort($code);
    }
}
