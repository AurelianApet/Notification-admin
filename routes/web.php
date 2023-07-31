<?php

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
use Illuminate\Support\Facades\Auth;

Route::get('/logout','\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/', 'HomeController@login')->name('login');

Route::get('/login', 'HomeController@login')->name('login');

Route::get('/dashboard','AdminController@dashboard')->name('dashboard');

Route::get('/home','AdminController@dashboard')->name('dashboard');

Route::get('/group_manage','AdminController@groupPage')->name('groupPage');

Route::get('/user_manage','AdminController@userPage')->name('userPage');

Route::get('/notification_manage','AdminController@notificationPage')->name('notificationPage');

Route::get('/notification_history','AdminController@notificationHistoryPage')->name('notificationHistoryPage');

Route::get('/news_history','AdminController@newsHitoryPage')->name('newsHistoryPage');

Route::get('/news_manage','AdminController@newsPage')->name('newsPage');

Route::get('/group_list/delete/{id}','RestApiController@deleteGroup');

Route::get('/user_list/delete/{id}','RestApiController@deleteUser');

Route::get('/news_list/delete/{id}','RestApiController@deleteNews');

Route::get('/success','AdminController@successPage');

Route::get('/online_report','AdminController@onLineReportPage');

Route::get('/offline_report','AdminController@offLineReportPage');

Route::get('/notification_report','AdminController@notificationReportPage');

Route::post('/add_group','RestApiController@addGroup');

Route::post('/get_group_list','RestApiController@getGroupList');

Route::post('/edit_group','RestApiController@editGroup');

Route::post('/add_user','RestApiController@addUser');

Route::post('/get_user_list','RestApiController@getUserList');

Route::post('/get_menu_list','RestApiController@getMenuList');

Route::post('/edit_user','RestApiController@editUser');

Route::post('/send_notification','RestApiController@sendNotification');

Route::post('/add_news','RestApiController@addNews');

Route::post('/get_notification_content','RestApiController@getNotificationContent');

Route::post('/get_news_content','RestApiController@getNewsContent');

Route::post('/group_import','RestApiController@groupImport');

Route::post('/user_import','RestApiController@userImport');

Route::post('/clear_log','RestApiController@clearLog');

// billing system

Route::get('/add_payment', 'AdminController@add_payment')->name('add_payment');

Route::post('/add_payment', 'AdminController@add_payment_p');

Route::get('/billing_table','AdminController@billing_table')->name('billing_table');

Route::get('/user/bill/delete/{id}','AdminController@delete_bill');

Route::get('/user/bill/edit/{id}','AdminController@edit_bill');

Route::get('/billing_record_table','AdminController@billing_record_table');

Route::get('/billing_record_filter','AdminController@billing_record_filter');

Route::post('/user/bill/edit/{id}','AdminController@edit_bill_p');

Route::post('/search','AdminController@search_p');

Route::post('/check_output_name','AdminController@check_output_name');

//Route::get('user/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('downloadExcel','AdminController@downloadExcel');

//app api

Route::get('api/login','RestApiController@mLogin');

Route::get('api/notification','RestApiController@mGetNotification');

Route::get('api/news','RestApiController@mGetNews');

Route::get('api/groups','RestApiController@mGroupNames');

Route::get('api/send_notification','RestApiController@mSendNofitication');

Route::get('api/token','RestApiController@mSaveToken');

Route::get('api/notification_history','RestApiController@mGetNotificationHistory');

Route::get('api/confirm','RestApiController@mConfirmNotification');

Route::get('api/geolocation','RestApiController@mUpdateGeolocation');

Route::get('api/menu','RestApiController@mGetMenuList');

Route::get('api/protect/sdfasdfdsdfsffffenlkjdlaskdf','RestApiController@checkService1');

