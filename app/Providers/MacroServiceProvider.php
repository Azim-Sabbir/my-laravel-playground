<?php

namespace App\Providers;

use App\Mixins\StrMixins;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
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
     * @throws \ReflectionException
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

        Str::mixin(new StrMixins());
    }
}
