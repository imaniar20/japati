<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetTahunKinerjaPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * request "tahun_kinerja_public_key" otomatis dikirim melalui interceptor axios
         * client\plugins\axios.js
         */
        if (config('app.tahun_kinerja_public_key') && $tahunKinerja = $request->header(config('app.tahun_kinerja_public_key'))) {
            setTahunKinerja($tahunKinerja);
        }

        return $next($request);
    }
}
