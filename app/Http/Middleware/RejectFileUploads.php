<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectFileUploads
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->allFiles() !== []) {
            return response()->error(
                'This endpoint does not accept file uploads.',
                422,
                ['files' => ['Remove file attachments from the request.']]
            );
        }

        return $next($request);
    }
}
