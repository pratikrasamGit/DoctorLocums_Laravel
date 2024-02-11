<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::post('get-specialities', 'ApiController@getSpecialities');
Route::post('get-work-location', 'ApiController@getGeographicPreferences');
Route::post('personal-detail', 'ApiController@personalDetail');
Route::post('availability', 'ApiController@availability');
Route::post('shift-duration', 'ApiController@shiftDuration');
Route::post('assignment-duration', 'ApiController@assignmentDurations');
Route::post('preferred-shifts', 'ApiController@preferredShifts');
Route::post('get-weekdays', 'ApiController@getWeekDay');
Route::post('state-list', 'ApiController@stateList');
Route::post('experience', 'ApiController@Experience');
Route::post('facility-types', 'ApiController@facilityTypes');
Route::post('nurse-experience-selections', 'ApiController@nurseExperienceSelectionOptions');
// Route::post('highest-nursing-degrees', 'ApiController@NursingDegrees');
Route::post('search-for-credentials-list', 'ApiController@searchForCredentialsOptions');
Route::post('media-options', 'ApiController@getMediaOptions');
Route::post('get-cerner-medtech-epic-options', 'ApiController@getEHRProficiencyExpOptions');
Route::post('nursing-degrees-options', 'ApiController@getNursingDegreesOptions');
// New Apis
Route::post('add-credentials', 'ApiController@addCredentials');
Route::post('edit-credentials', 'ApiController@editCredentials');
Route::post('remove-credentials-image', 'ApiController@removeCredentialDoc');
Route::post('get-leadership-roles', 'ApiController@leadershipRoles');
Route::post('get-languages-list', 'ApiController@getLanguages');
Route::post('role-and-interest/page-1', 'ApiController@rolePage1');
Route::post('role-and-interest/page-2', 'ApiController@rolePage2');
Route::post('remove-role-interest-doc', 'ApiController@destroyRoleInterestDocument');
Route::post('browse-jobs', 'ApiController@jobList'); //incomplete
Route::post('view-job', 'ApiController@viewJob'); //incomplete
Route::post('job-applied', 'ApiController@jobApplied');
Route::post('job-like', 'ApiController@jobLikes'); //incomplete
Route::post('browse-facility', 'ApiController@browse_facilities');
Route::post('facility-follow', 'ApiController@facilityFollows');
Route::post('facility-like', 'ApiController@facilityLikes');
Route::post('job-offers', 'ApiController@jobOffered');
Route::post('job-accept', 'ApiController@jobAcceptPost');
Route::post('job-reject', 'ApiController@jobRejectPost'); //incomplete
Route::post('job-active', 'ApiController@jobActive'); //incomplete
Route::post('job-completed', 'ApiController@jobCompleted'); //incomplete
Route::post('get-notification', 'ApiController@notification');
Route::post('remove-notification', 'ApiController@removeNotification');
Route::post('settings', 'ApiController@settings');
Route::post('get-nurse-profile', 'ApiController@NurseProfileInfo');
Route::post('get-emedical-records', 'ApiController@getEMedicalRecordsOptions');
Route::post('update-profile-picture', 'ApiController@profilePictureUpload');
Route::post('update-role-interest', 'ApiController@updateRoleInterest');
Route::post('nurse-resume', 'ApiController@resume');
Route::post('terms-conditions', 'ApiController@termsAndConditions');
Route::post('privacy-policy', 'ApiController@privacyPolicy');
Route::post('about-app', 'ApiController@aboutAPP');
Route::post('change-password', 'ApiController@changePassword');
Route::post('forgot-password', 'ApiController@sendResetLinkEmail');
Route::post('view-job-detail', 'ApiController@viewJobOffered');
Route::post('facility-rating', 'ApiController@facilityRatings');
Route::post('new-phone-number', 'ApiController@newPhoneNumber');
Route::post('confirm-otp', 'ApiController@confirmOTP');
Route::post('get-countries', 'ApiController@getCountries');
Route::post('get-states', 'ApiController@getStates');
Route::post('get-cities', 'ApiController@getCities');

/* facility */
Route::post('facility-dropdown-{type}', 'ApiController@facilityDropdown');
Route::post('facility-profile', 'ApiController@facilityDetail');
Route::post('change-facility-logo', 'ApiController@changeFacilityLogo');
Route::post('browse-nurses', 'ApiController@browseNurses');
Route::post('get-seniority-level', 'ApiController@getSeniorityLevelOptions');
// don't change these two lines
Route::post('job-offered-{type}', 'ApiController@offeredNurses');
Route::post('job-{type}', 'ApiController@createJob');
// don't change these two lines
Route::post('get-job-function', 'ApiController@getJobFunctionOptions');
Route::post('send-offer', 'ApiController@apiJobInvite');
Route::post('my-jobs-{type}', 'ApiController@facilityPostedJobs');
Route::post('offer-job-to-nurse-dropdown', 'ApiController@apiJobsList');
Route::post('job-info-short', 'ApiController@apiJobFacility');
Route::post('nurses-applied-jobs', 'ApiController@appliedNurses');
Route::post('nurse-rating', 'ApiController@nurseRating');
Route::post('remove-job-asset', 'ApiController@removeJobDocument');
Route::post('facility-settings', 'ApiController@settingsFacility');
Route::post('facility-notifications', 'ApiController@notificationFacility');
/* facility */
Route::post('get-user-images', 'ApiController@userImages');

Route::post('country/{name}/states', 'ProfileController@stateJson')->name('state-json');
Route::post('testing', 'ApiController@test');
