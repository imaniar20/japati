<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SetTahunKinerja;
use App\Http\Middleware\SetTahunKinerjaPublic;
use App\Models\ValidasiPerencanaan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetTahunKinerja::class);
        $middleware->append(
            \Illuminate\Http\Middleware\HandleCors::class
        );

        $middleware->alias([
            'tahun-kinerja-public' => SetTahunKinerjaPublic::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('sync:parent-skp', ['--tahun' => 2025])->everyFifteenMinutes();
    })
    ->create();
