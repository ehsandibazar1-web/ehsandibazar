<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:web'], 'prefix' => 'panel'], function () {
    Route::group(['middleware' => ['checkCustomer','optimizeImages'], 'namespace' => 'Users', 'prefix' => 'users'], function () {


        Route::get('/', 'HomeController@index')->name('users.dashboard.index');

        // ================================================= Start Of Ticket =============
        Route::group(['prefix' => 'ticket'], function () {
            Route::get('/', 'TicketController@index')->name('users.ticket.index');
            Route::get('/send/', 'TicketController@SendTicket')->name('users.ticket.sent');
            Route::post('/store/', 'TicketController@ticketStore')->name('users.ticket.store');
            Route::get('/show/{ticket}', 'TicketController@show')->name('users.ticket.show');
            Route::post('/ticket/send-answer/{ticket}', 'TicketController@SendTicketAnswer')->name('users.ticket.answer.sent');
        });
        // ================================================= End Of Ticket =============

        // ================================================= Start Of orders =============
        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', 'OrderController@index')->name('users.panel.order.index');
        });
        // ================================================= End Of orders =============


        // ================================================= Start Of My Auctions =============
        Route::group(['prefix' => 'my-auctions'], function () {
            Route::get('/', 'HomeController@myAuctions')->name('users.panel.auctions');
        });
        // ================================================= End Of My Auctions =============


        // ================================================= Start Of my-book =============
        Route::group(['prefix' => 'my-book'], function () {
            Route::get('/', 'HomeController@myBook')->name('users.panel.book');
            Route::get('/{product}', 'HomeController@showBook')->name('users.panel.showBook');
            Route::get('play-voice/{file}', 'HomeController@playVoice')->name('users.panel.playVoice');        });
        // ================================================= End Of my-book =============


        // ================================================= Start Of users-profile =============
        Route::group(['prefix' => 'users-profile'], function () {
            Route::get('/', 'UserProfileController@index')->name('users.panel.profile');
            Route::post('/update', 'UserProfileController@update')->name('users.panel.profileUpdate');
            Route::get('/changePw/', 'UserProfileController@ChangePwFrom')->name('users.panel.changePwFrom');
            Route::post('/changePw/', 'UserProfileController@ChangePw')->name('users.change.password');
            Route::get('/address', 'UserProfileController@addressUser')->name('users.panel.address');
            Route::post('/address/store', 'UserProfileController@StoreAddress')->name('users.panel.storeAddress');
            Route::get('/address/delete/{id}', 'UserProfileController@DeleteAddress')->name('users.delete.address');
            Route::post('/ajaxCity', 'UserProfileController@ajaxCity')->name('users.panel.profile.ajaxCity');
        });
        // ================================================= End Of users-profile =============


        // ================================================= Start Of favorites =============
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', 'FavriteController@index')->name('users.panel.favorite.index');;
            Route::get('/delete/{id}', 'FavriteController@delete')->name('users.panel.favorite.delete');
        });
        // ================================================= End Of favorites =============

    });
});

