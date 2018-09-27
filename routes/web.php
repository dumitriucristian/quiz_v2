<?php
use App\Tag;
use App\Http\Resources\TagCollection;
use App\Category;
use App\Http\Resources\CategoryCollection;



Auth::routes();

Route::get('/getCategories', function () {
    return new CategoryCollection(Category::all());
});

Route::get('/categoryTree', function () {
    return Category::categoryTree();
});


Route::get('/questionTags/{questionId}', function ($questionId) {

    return new TagCollection(Tag::questionTags($questionId));
});


Route::get('/guest-login', 'LoginController@login');
Route::get('/loginAsGuest', 'LoginController@loginAsGuest');


Route::get('/lastQuestion/{user_quiz_id}', 'QuizzesController@lastQuestion');

Route::get('/', 'QuizzesController@homePage');
Route::get('/home','QuizzesController@homePage');
Route::get('/quiz/{quiz_id}/{user_quiz_id?}',   'QuizzesController@quizDetails');

Route::post('/admin/addCategory',           'AdminController@addCategory')->middleware('admin');
Route::get('/admin/addQuestionCategory/{questionId}/{categoryId}',  'AdminController@addQuestionCategory');
Route::post('/admin/addTag',                'AdminController@addTag')->middleware('admin');
Route::get('/admin/categories',            'AdminController@categories')->middleware('admin');
Route::get('/admin/tags',                  'AdminController@tags')->middleware('admin');
Route::get('/admin/{questionId}/addAnswer', 'AdminController@addAnswer')->middleware('admin');
Route::get('/admin/addQuiz',                'AdminController@addQuiz')->middleware('admin');
Route::get('/admin/addQuestion',            'AdminController@addQuestion')->middleware('admin');

Route::get('/admin/{idQuiz}/editQuiz',      'AdminController@editQuiz')->middleware('admin');
Route::post('/admin/saveQuiz',              'AdminController@saveQuiz')->middleware('admin');
Route::post('/admin/saveQuestion',          'AdminController@saveQuestion')->middleware('admin');
Route::post('/admin/updateQuestion',        'AdminController@updateQuestion')->middleware('admin');
Route::post('/admin/saveAnswer',            'AdminController@SaveAnswer')->middleware('admin');
Route::get('/admin/quizzes',                'AdminController@quizzes')->middleware('admin');
Route::get('/admin/{quizId}/removeQuiz',    'AdminController@removeQuiz')->middleware('admin');
Route::post('/admin/updateAnswer',          'AdminController@updateAnswer')->middleware('admin');
Route::get('/admin/{quizId}/{questionId}/removeQuestion','AdminController@removeQuestion')->middleware('admin');

Route::get('/admin/addQuestionTag/{questionId}/{tagId}', 'AdminController@addQuestionTag');
Route::get('/admin/removeQuestionTag/{questionId}/{tagId}', 'AdminController@removeQuestionTag');

Route::get('resetQuiz/{quiz_id}/{user_quiz_id}', 'QuizzesController@resetQuiz');
Route::get('continueQuiz/{user_quiz_id}', 'QuizzesController@continueQuiz');

Route::get('addResult/{user_quiz_id}','ResultsController@addResult');
Route::get('result/{user_quiz_id}','ResultsController@index');

Route::post('addUserAnswer','QuizzesController@addUserAnswer');




