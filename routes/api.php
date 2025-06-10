<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\RectifierController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\AcuController;
use App\Http\Controllers\NetworkElementController;
use App\Http\Controllers\DcPanelController;
use App\Http\Controllers\DcPanelItemController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\SiteCategoryController;
use App\Http\Controllers\SubDomainController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\GensetController;
use App\Http\Controllers\OspmController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});

// Route::group([
//     'middleware' => 'api',
// ], function ($router) {
    Route::apiResource('battery', BatteryController::class);
    Route::post("v1/battery/dtables", [BatteryController::class, 'allDataTables']);
    Route::get("v1/battery/select2", [BatteryController::class, 'allSelect2']);
    Route::post("v1/battery/update", [BatteryController::class, 'update']);
    Route::post("v1/battery/delete", [BatteryController::class, 'destroy']);
    Route::post("v1/battery/summary", [BatteryController::class, 'summary']);
    Route::get("v1/battery/export", [BatteryController::class, 'export']);
// });
  
    Route::apiResource('site', SiteController::class);
    Route::post("v1/site/default", [SiteController::class, 'allDefault']);
    Route::post("v1/site/dtables", [SiteController::class, 'allDataTables']);
    Route::get("v1/site/select2", [SiteController::class, 'allSelect2']);
    Route::post("v1/site/update", [SiteController::class, 'update']);
    Route::post("v1/site/delete", [SiteController::class, 'destroy']);
    Route::get("v1/site/export", [SiteController::class, 'export']);
    Route::post("v1/site/get-count-site-category", [SiteController::class, 'getSummaryPerSiteCategory']);
    Route::post("v1/site/get-count-region", [SiteController::class, 'getSummaryPerRegion']);
    
    Route::apiResource('rectifier', RectifierController::class);
    Route::post("v1/rectifier/dtables", [RectifierController::class, 'allDataTables']);
    Route::get("v1/rectifier/select2", [RectifierController::class, 'allSelect2']);
    Route::post("v1/rectifier/update", [RectifierController::class, 'update']);
    Route::post("v1/rectifier/delete", [RectifierController::class, 'destroy']);

    Route::apiResource('acu', AcuController::class);
    Route::post("v1/acu/dtables", [AcuController::class, 'allDataTables']);
    Route::get("v1/acu/select2", [AcuController::class, 'allSelect2']);
    Route::post("v1/acu/update", [AcuController::class, 'update']);
    Route::post("v1/acu/delete", [AcuController::class, 'destroy']);

    Route::apiResource('genset', GensetController::class);
    Route::post("v1/genset/dtables", [GensetController::class, 'allDataTables']);
    Route::get("v1/genset/select2", [GensetController::class, 'allSelect2']);
    Route::post("v1/genset/update", [GensetController::class, 'update']);
    Route::post("v1/genset/delete", [GensetController::class, 'destroy']);

    Route::apiResource('ne', NetworkElementController::class);
    Route::post("v1/ne/dtables", [NetworkElementController::class, 'allDataTables']);
    Route::get("v1/ne/select2", [NetworkElementController::class, 'allSelect2']);
    Route::post("v1/ne/default", [NetworkElementController::class, 'allDefault']);
    Route::post("v1/ne/update", [NetworkElementController::class, 'update']);
    Route::post("v1/ne/delete", [NetworkElementController::class, 'destroy']);
    Route::get("v1/ne/export", [NetworkElementController::class, 'export']);

    Route::apiResource('dc-panel', DcPanelController::class);
    Route::post("v1/dc-panel/dtables", [DcPanelController::class, 'allDataTables']);
    Route::get("v1/dc-panel/select2", [DcPanelController::class, 'allSelect2']);
    Route::post("v1/dc-panel/update", [DcPanelController::class, 'update']);
    Route::post("v1/dc-panel/delete", [DcPanelController::class, 'destroy']);

    Route::apiResource('dc-panel-item', DcPanelItemController::class);
    Route::post("v1/dc-panel-item/dtables", [DcPanelItemController::class, 'allDataTables']);
    Route::get("v1/dc-panel-item/select2", [DcPanelItemController::class, 'allSelect2']);
    Route::post("v1/dc-panel-item/update", [DcPanelItemController::class, 'update']);
    Route::post("v1/dc-panel-item/delete", [DcPanelItemController::class, 'destroy']);

    Route::post('/v1/geo/regions/dtables', [GeoController::class, 'allDataTablesRegion']);

    Route::post('/v1/geo/regions/select2', [GeoController::class, 'getAllRegions']);
    Route::post('/v1/geo/provinces/select2', [GeoController::class, 'getAllProvinces']);
    Route::post('/v1/geo/towns/select2', [GeoController::class, 'getAllTowns']);
    Route::post('/v1/geo/barangays/select2', [GeoController::class, 'getAllBrgys']);
    Route::get('/v1/geo/nothing', [GeoController::class, 'getNothing']);
    

    // libraries
    Route::apiResource('manufacturer', ManufacturerController::class);
    Route::post("v1/manufacturer/dtables", [ManufacturerController::class, 'allDataTables']);
    Route::get("v1/manufacturer/select2", [ManufacturerController::class, 'allSelect2']);
    Route::post("v1/manufacturer/update", [ManufacturerController::class, 'update']);
    Route::post("v1/manufacturer/delete", [ManufacturerController::class, 'destroy']);

    Route::apiResource('site-category', SiteCategoryController::class);
    Route::post("v1/site-category/dtables", [SiteCategoryController::class, 'allDataTables']);
    Route::get("v1/site-category/select2", [SiteCategoryController::class, 'allSelect2']);
    Route::post("v1/site-category/update", [SiteCategoryController::class, 'update']);
    Route::post("v1/site-category/delete", [SiteCategoryController::class, 'destroy']);
    

    Route::apiResource('sub-domain', SubDomainController::class);
    Route::post("v1/sub-domain/dtables", [SubDomainController::class, 'allDataTables']);
    Route::get("v1/sub-domain/select2", [SubDomainController::class, 'allSelect2']);
    Route::post("v1/sub-domain/update", [SubDomainController::class, 'update']);
    Route::post("v1/sub-domain/delete", [SubDomainController::class, 'destroy']);

    Route::apiResource('organization', OrganizationController::class);
    // Route::post("v1/organization/dtables", [OrganizationController::class, 'allDataTables']);
    Route::get("v1/organization/select2", [OrganizationController::class, 'allSelect2']);
    // Route::post("v1/organization/update", [OrganizationController::class, 'update']);
    // Route::post("v1/organization/delete", [OrganizationController::class, 'destroy']);




    Route::apiResource('ospm', OspmController::class);
    Route::post("v1/ospm/dtables", [OspmController::class, 'allDataTables']);
    Route::get("v1/ospm/select2", [OspmController::class, 'allSelect2']);
    Route::post("v1/ospm/update", [OspmController::class, 'update']);
    Route::post("v1/ospm/delete", [OspmController::class, 'destroy']);
    Route::get("v1/ospm/export", [OspmController::class, 'export']);
    Route::post("v1/ospm/summary", [OspmController::class, 'summary']);
