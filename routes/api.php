<?php

use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\TemplateController;
use App\Http\Controllers\Api\V1\TemplateVersionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/healthz', HealthController::class)->name('v1.healthz');

    Route::middleware('api.key')->group(function () {
        Route::get('/templates', [TemplateController::class, 'index'])->name('v1.templates.index');
        Route::post('/templates', [TemplateController::class, 'store'])->name('v1.templates.store');
        Route::get('/templates/{templateId}', [TemplateController::class, 'show'])->name('v1.templates.show');
        Route::patch('/templates/{templateId}', [TemplateController::class, 'update'])->name('v1.templates.update');
        Route::delete('/templates/{templateId}', [TemplateController::class, 'destroy'])->name('v1.templates.destroy');
        Route::get('/templates/{templateId}/fields', [TemplateController::class, 'fields'])->name('v1.templates.fields');

        Route::get('/templates/{templateId}/versions', [TemplateVersionController::class, 'index'])->name('v1.templates.versions.index');
        Route::post('/templates/{templateId}/versions', [TemplateVersionController::class, 'store'])->name('v1.templates.versions.store');
        Route::get('/templates/{templateId}/versions/{label}', [TemplateVersionController::class, 'show'])->name('v1.templates.versions.show');
    });
});
