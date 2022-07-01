<?php

namespace App\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('customResponse', function ($data = "", $message = "", $code = ResponseAlias::HTTP_OK){
            return response()->json([
                "data" => $data,
                "message" => $message,
                "code" => $code,
            ]);
        });
    }
}
