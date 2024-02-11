<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index');

Route::resource('profile-setup', 'ProfileController', [
    'except' => ['edit', 'update', 'show', 'create', 'store', 'destroy']
]);
Route::get('profile-setup/{nurse}', 'ProfileController@personalDetail')->name('personal-detail');
Route::post('profile-setup/{nurse}', 'ProfileController@personalDetailPost');
Route::get('profile-setup/{nurse}/schedule-onboarding', 'ProfileController@scheduleOnboarding')->name('schedule-onboarding');
Route::post('profile-setup/{nurse}/schedule-onboarding', 'ProfileController@scheduleOnboardingPost');
Route::get('profile-setup/{nurse}/availability', 'ProfileController@availability')->name('availability');
Route::post('profile-setup/{nurse}/availability', 'ProfileController@availabilityPost');
Route::get('profile-setup/{nurse}/work-history', 'ProfileController@certifications')->name('work-history');
Route::post('profile-setup/{nurse}/work-history', 'ProfileController@certificationsPost');
Route::get('profile-setup/{nurse}/work-history/add', 'ProfileController@addWorkHistory')->name('add-work-history');
Route::get('profile-setup/{nurse}/work-history/{experience}/edit', 'ProfileController@editWorkHistory')->name('edit-work-history');
Route::post('profile-setup/{nurse}/work-history/{experience}/edit', 'ProfileController@editWorkHistoryPost');
Route::get('profile-setup/{nurse}/work-history/credentials/add', 'ProfileController@addCredential')->name('nurse-add-credentials');
Route::get('profile-setup/{nurse}/work-history/credentials/{certification}/edit', 'ProfileController@editCredential')->name('nurse-edit-credentials');
Route::post('profile-setup/{nurse}/work-history/credentials/{certification}/edit', 'ProfileController@editCredentialPost');
Route::get('profile-setup/{nurse}/credential/{certification}/remove', 'ProfileController@destroyCertifications')->name('nurse-credential.remove');
Route::get('profile-setup/{nurse}/certificate/{certification}/remove', 'ProfileController@destroyCredDocument')->name('nurse-cred.remove');
Route::get('profile-setup/{nurse}/certificate/{certification}/download', 'ProfileController@downloadCredDocument')->name('nurse-cred.download');
Route::get('profile-setup/{nurse}/asset/{nurse_asset}/remove', 'ProfileController@destroyDocument')->name('nurse-file.remove');
Route::get('profile-setup/{nurse}/asset/{nurse_asset}/download', 'ProfileController@download')->name('nurse-file.download');
Route::get('profile-setup/{nurse}/resume/download', 'ProfileController@downloadResume')->name('nurse-cv.download');
Route::get('profile-setup/{nurse}/role-interest', 'ProfileController@roleInterest')->name('role-interest');
Route::post('profile-setup/{nurse}/role-interest', 'ProfileController@roleInterestPost');

Route::get('profile-setup/{facility}/facility', 'ProfileController@facilityDetail')->name('facility-detail');
Route::post('profile-setup/{facility}/facility', 'ProfileController@facilityDetailPost');

Route::get('profile-setup/{nurse}/setup-direct-deposite', 'ProfileController@createGigwageAccount')->name('create-gigwage-account');
Route::post('profile-setup/{nurse}/setup-direct-deposite', 'ProfileController@createGigwageAccountPost');
Route::get('profile-setup/{nurse}/setup-direct-deposite/invite/{id}', 'ProfileController@inviteGigwageAccount')->name('invite-gigwage-account');

Route::get('test-email', 'ProfileController@testEmail');
Auth::routes();
Route::get('admin', 'DashboardController@index');
Route::any('adminer', '\Aranyasen\LaravelAdminer\AdminerController@index')->middleware(['role:Administrator']);
Route::group(['prefix' => 'admin', 'middleware' => ['role:Administrator|Admin']], function () {
    Route::resource('keywords', 'KeywordController', [
        'except' => ['show']
    ]);
    Route::resource('roles', 'NuRolesController', [
        'except' => ['show']
    ]);
    Route::resource('permissions', 'NuPermissionsController', [
        'except' => ['show']
    ]);
    Route::get('nurses/trashed', 'NurseController@trashed');
    Route::get('nurses/restore/{id}', 'NurseController@restore')->name('nurses-restore');
    Route::get('nurses/search', 'NurseController@search');
    Route::post('nurses/search', 'NurseController@search');
    Route::resource('nurses', 'NurseController', [
        'except' => ['show']
    ]);
    Route::get('nurses/{nurse}/credentials/add', 'NurseController@addCredential')->name('add-credentials');
    Route::get('nurses/{nurse}/credentials/{certification}/edit', 'NurseController@editCredential')->name('edit-credentials');
    Route::post('nurses/{nurse}/credentials/{certification}/edit', 'NurseController@editCredentialPost');
    Route::get('nurses/{nurse}/work-history/add', 'NurseController@addWorkHistory')->name('admin-add-work-history');
    Route::get('nurses/{nurse}/work-history/{experience}/edit', 'NurseController@editWorkHistory')->name('admin-edit-work-history');
    Route::post('nurses/{nurse}/work-history/{experience}/edit', 'NurseController@editWorkHistoryPost');
    Route::get('nurses/{nurse}/resume/download', 'NurseController@downloadResume')->name('cv.download');
    Route::get('nurses/{nurse}/resume/download/m', 'NurseController@mediaDownloadResume')->name('cv.media.download');
    Route::resource('facilities', 'FacilitiesController', [
        'except' => ['show']
    ]);
    Route::get('facilities/{user}/resetpassword', 'FacilitiesController@reset_password');
    Route::get('facilities/search', 'FacilitiesController@search');
    Route::post('facilities/search', 'FacilitiesController@search');
    Route::resource('jobs', 'JobController', [
        'except' => ['show']
    ]);
    Route::get('job-offers', 'JobController@job_offers');
    Route::resource('adminusers', 'AdminUsersController', [
        'except' => ['show']
    ])->parameters([
        'adminusers' => 'user'
    ]);
    Route::resource('departments', 'DepartmentController', [
        'except' => ['show']
    ]);
    Route::get('users/{user}/departments/{department}/detach', 'DepartmentController@detachUser');
    Route::get('users/{user}/facilities/{facility}/detach', 'FacilitiesController@detachUser');
    Route::get('departmentusers/{department}/create', 'DepartmentUsersController@create');
    Route::post('departmentusers/{department}', 'DepartmentUsersController@store');
    Route::resource('departmentusers', 'DepartmentUsersController', [
        'except' => ['show', 'create', 'store', 'destroy']
    ])->parameters([
        'departmentusers' => 'user'
    ]);

    Route::get('facilityusers/{facility}/create', 'FacilityUsersController@create');
    Route::post('facilityusers/{facility}', 'FacilityUsersController@store');
    Route::resource('facilityusers', 'FacilityUsersController', [
        'except' => ['show', 'create', 'store', 'destroy']
    ])->parameters([
        'facilityusers' => 'user'
    ]);
    Route::get('users/{user}/send-invite', 'UsersController@sendInvite')->name('invite-users');
    Route::get('export-nurses', 'NurseController@nurses_export');
    /* email template routes*/
    Route::get('email-template/{template}/edit', 'EmailTemplateController@edit')->name('edit-template');
    Route::get('email-template/view/{template}', 'EmailTemplateController@show')->name('view-template');
    Route::resource('email-template', 'EmailTemplateController', [
        'except' => ['create', 'store', 'destroy']
    ])->parameters([
        'email-template' => 'user'
    ]);
    /* email template routes*/
});
Route::get('splash-image/{filename}', function ($filename) {
    return response()->file(
        storage_path() . '/assets/splash/' . $filename
    );
});
Route::middleware('role:Administrator|Admin|FacilityAdmin|Facility')->group(function () {
    Route::get('browse-nurses', 'PageController@browse_nurses')->name('browse-nurses');
    // Route::get('browse-nurses/search', 'PageController@search_nurses')->name('search-nurses');
    // Route::post('browse-nurses/search', 'PageController@search_nurses');
    Route::get('browse-nurses/{slug}', 'PageController@view_nurse');
});
Route::middleware('role:Administrator|Admin|Nurse')->group(function () {
    Route::get('browse-facilities', 'PageController@browse_facilities')->name('browse-facilities');
    Route::get('browse-facilities/{facility}', 'PageController@view_facility');
    Route::get('browse-jobs', 'PageController@browse_jobs')->name('browse-jobs');
    Route::post('browse-jobs/{job}/accept', 'PageController@jobAcceptPost');
    Route::post('browse-jobs/{job}/reject', 'PageController@jobRejectPost');
});
Route::middleware('role:Administrator|Admin|Nurse|FacilityAdmin|Facility')->group(function () {
    Route::get('browse-jobs/{job}', 'PageController@view_job');
});
Route::get('departments', 'DepartmentController@index');
Route::get('department/create', 'DepartmentController@create');
Route::post('department/create', 'DepartmentController@store');
Route::get('department/{department}/edit', 'DepartmentController@edit');
Route::put('department/{department}', 'DepartmentController@update');
Route::get('jobs', 'JobController@index');
// Route::put('jobs/{type}', 'JobController@index');
// Route::get('jobs/inactive', 'JobController@jobsInactive')->name('jobs.inactive');
Route::get('job/create', 'JobController@create');
Route::post('job/create', 'JobController@store');
Route::get('job/{job}/edit', 'JobController@edit');
Route::put('job/{job}', 'JobController@update');
Route::get('job/{job}/asset/{job_asset}/remove', 'JobController@destroyDocument');
Route::get('api/{nurse}/jobs', 'JobController@apiJobsList')->name('api-jobs-list');
Route::get('api/job/{job}/facility', 'JobController@apiJobFacility')->name('api-job-facility');
Route::get('api/job/{job}/invite/{nurse}', 'JobController@apiJobInvite')->name('api-job-invite');
Route::get('profile', 'PageController@view_profile');
Route::get('nurse/{nurse}/asset/{nurse_asset}/remove', 'NurseController@destroyDocument')->name('file.remove');
Route::get('nurse/{nurse}/asset/{nurse_asset}/download', 'NurseController@download')->name('file.download');
Route::get('nurse/{nurse}/credential/{certification}/remove', 'NurseController@destroyCertifications')->name('credential.remove');
Route::get('nurse/{nurse}/certificate/{certification}/remove', 'NurseController@destroyCredDocument')->name('cred.remove');
Route::get('nurse/{nurse}/certificate/{certification}/download', 'NurseController@downloadCredDocument')->name('cred.download');
Route::get('page/{nurse}/asset/{nurse_asset}/download', 'PageController@assetDownload')->name('assetDownload');
Route::get('page/{nurse}/certificate/{certification}/download', 'PageController@downloadCredDocument')->name('credDownload');
Route::get('page/{nurse}/resume/download', 'PageController@downloadResume')->name('nurse-cv.download.profile');
Route::get('offers/{nurse}', 'PageController@nurseOffers')->name('nurse-offer')->middleware('access.offers');
Route::get('updatepwd/new/{token}/{emailID}', 'GuestController@updatePass')->name('update-pwd')->middleware('signed');
Route::post('updatepwd/new/{token}/{emailID}', 'GuestController@updatePassPost')->name('update-pwd-post')->middleware('signed');
Route::get('{nurse}/resume/view/m', 'PageController@view_resume_media')->name('resume.view.media.nurse');

/* 22/dec/2021 */
Route::post('get-states-by-country', 'PageController@getStateByCountry');
Route::post('get-cities-by-state', 'PageController@getCityByState');
/* 22/dec/2021 */

Route::get('terms-conditions', 'TermsCondtionsController@index')->name('terms-conditions'); // 19jan2022
Route::get('privacy-policy', 'PrivacyPolicyController@index')->name('privacy-policy'); // 18feb2022

/* 02/Mar/2022 */
Route::post('get-facility-rating', 'PageController@getAdminFacilityRating');
Route::post('update-facility-rating', 'PageController@updateFacilityRating');

Route::post('get-nurse-rating', 'PageController@getAdminNurseRating');
Route::post('update-nurse-rating', 'PageController@updateNurseRating');
/* 02/Mar/2022 */

/* 28/03/2022 */
Route::get('cron-weekly-update', 'CronController@notifyFacilityWeekly')->name('cron-weekly-update');
/* 28/03/2022 */
