<?php
use Illuminate\Support\Facades\Auth;

Route::get('test',function(){
    return view('test');
});
//----------------------------------------------------------------------------
// HOME //
Route::auth();
Route::get('/', 'HomeController@index')->name('index');

//----------------------------------------------------------------------------
// USER //
Route::group(['prefix' => 'do-test'],function(){
    // Show All Subjects
    Route::get('/','UserController@index');
    Route::group(['prefix' => '{subject_id}'],function(){
        // Show All Of Test In A Subject
        Route::get('/','UserController@showTests')->where('subject_id','[\d]+');
        // Ajax Load Test
        Route::post('/','UserController@doTest');
        // Ajax Click Input
        Route::post('doQuestion','UserController@doQuestion');
        // Show Done Questions Of A Test
        Route::get('{test_id}','UserController@showResults')->name('UserController.showResults')->middleware('checkDoTest');
        Route::post('{test_id?}','UserController@doTest')->where('test_id','[\d]*');
        // Submit When User Finishes Doing Test
        Route::post('{test_id}','UserController@showResults');
        // Ajax Load True/False Questions
        Route::post('{test_id}/showQuestions','UserController@showQuestions');
    });
});

//----------------------------------------------------------------------------
// ADMIN //
Route::group([ 'prefix' => 'admin'], function () {
    Route::get('/', 'ManageController@index');

    Route::group(['prefix' => 'manageQuestion'], function () {
        Route::get('/', 'ManageController@showSubjects');
        Route::post('/', 'ManageController@createSubject');

        Route::group(['prefix' => '{subject_id}'], function () {
            Route::get('/', 'ManageController@showUnits');
            Route::post('/', 'ManageController@createUnit');

            Route::group(['prefix' => '{unit_id}'], function () {
                Route::get('/', 'ManageController@showTests');
                Route::post('/', 'ManageController@createTest');

                Route::group(['prefix' => '{test_id}'], function () {
                    Route::get('/', 'ManageController@showQuestions');
                    Route::get('/delete', 'ManageController@destroyTest');
                    Route::post('/create','ManageController@createMoreQuestions');
                    Route::post('/','ManageController@updateQuestions');
                });
            });
        });
    });
});

//----------------------------------------------------------------------------
// Unvalid URL //
Route::get('/{temp}',function(){return redirect()->route('index');});
