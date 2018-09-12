<?php


Auth::routes();

Route::get('/guest-login', 'LoginController@index');
Route::get('/loginAsGuest', 'LoginController@loginAsGuest');



Route::get('/lastQuestion/{user_quiz_id}', 'QuizzesController@lastQuestion');

Route::get('/', 'QuizzesController@homePage');
Route::get('/home','QuizzesController@homePage');
Route::get('/quiz/{quiz_id}/{user_quiz_id?}',   'QuizzesController@quizDetails');


Route::get('/admin/{questionId}/addAnswer', 'AdminController@addAnswer');
Route::get('/admin/addQuiz',                'AdminController@addQuiz');
Route::get('/admin/addQuestion',            'AdminController@addQuestion');

Route::get('/admin/{idQuiz}/editQuiz',      'AdminController@editQuiz');
Route::post('/admin/saveQuiz',              'AdminController@saveQuiz');
Route::post('/admin/saveQuestion',          'AdminController@saveQuestion');
Route::post('/admin/updateQuestion',        'AdminController@updateQuestion');
Route::post('/admin/saveAnswer',        'AdminController@SaveAnswer');
Route::get('/admin/quizzes',                'AdminController@quizzes');
Route::get('/admin/{quizId}/removeQuiz',    'AdminController@removeQuiz');
Route::post('/admin/updateAnswer',          'AdminController@updateAnswer');
Route::get('/admin/{quizId}/{questionId}/removeQuestion','AdminController@removeQuestion');

Route::get('resetQuiz/{quiz_id}/{user_quiz_id}', 'QuizzesController@resetQuiz');
Route::get('continueQuiz/{user_quiz_id}', 'QuizzesController@continueQuiz');

Route::get('addResult/{user_quiz_id}','ResultsController@addResult');
Route::get('result/{user_quiz_id}','ResultsController@index');

Route::post('addUserAnswer','QuizzesController@addUserAnswer');




