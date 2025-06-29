<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Exports\EventSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventPivotExport;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', function () {
    return view('welcome');
});


