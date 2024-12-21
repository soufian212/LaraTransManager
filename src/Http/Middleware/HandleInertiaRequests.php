<?php

namespace Soufian212\LaraTransManager\Http\Middleware ;

use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'laratransmanager::app';

   
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

   
    public function share(Request $request): array
    {

        return array_merge(parent::share($request), [
            'toast' => function () use ($request) {
                return session('toast');
            },
        ]);
    }
}