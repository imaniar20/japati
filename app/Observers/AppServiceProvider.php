<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\KinerjaProgram;
use App\Models\KinerjaKegiatan;
use App\Observers\KinerjaProgramObserver;
use App\Observers\KinerjaKegiatanObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        KinerjaProgram::observe(KinerjaProgramObserver::class);
        KinerjaKegiatan::observe(KinerjaKegiatanObserver::class);
    }
}