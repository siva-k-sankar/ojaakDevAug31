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

    /*Route::get('/', function () {
        return view('welcome');
    });*/
    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        return "Cache is cleared";
    });
    
    Route::get('/cronjob', function() {
        //Artisan::call('UserVerified:cron');
        //Artisan::call('ojaakpoint:cron');
        // Artisan::call('wallet:cron');
        //Artisan::call('validitiy:cron');
        Artisan::call('walletvalidity:cron');
        //Artisan::call('Broadcast:send');
        return "cron run";
    });

    Route::get('/', 'HomeController@welcome')->name('welcome');
    Route::get('/invalidLoginError', 'HomeController@invalidLoginError')->name('invalidLoginError');
    Route::get('/ajaxlogout', '\App\Http\Controllers\Auth\LoginController@logout')->name('ajaxlogout');
    Route::get('searchlocation', 'HomeController@searchlocation')->name('searchlocation');
    Route::get('search', 'HomeController@search')->name('search');
    
    Route::get('about-us', 'PagesController@aboutus')->name('aboutus');
    Route::get('termscondition', 'PagesController@termscondition')->name('termscondition');
    Route::get('sitemap', 'PagesController@sitemap')->name('sitemap');
    Route::get('privacypolicy', 'PagesController@privacypolicy')->name('privacypolicy');
    Route::get('howitswork', 'PagesController@howitswork')->name('howitswork');
    Route::get('help', 'PagesController@help')->name('help');
    
    //Auth::routes();
    Auth::routes(['verify' => true,'register' => false]);

    Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
    Route::get('/callback/{provider}', 'SocialController@callback');

    Route::post('/ajaxsubcate', 'PostController@ajaxsubcate')->name('ajaxsubcate')->middleware('users');

    Route::get('/post', 'PostController@index')->name('ads.post')->middleware('users');
    Route::get('/post/Prevent', 'AdsController@adspostPrevent')->name('ads.prevent')->middleware('users');
    Route::post('/post/Prevent/update', 'AdsController@preventadsupdate')->name('ads.prevent.update');
    Route::post('/post/Prevent/clear', 'AdsController@preventadsReset')->name('ads.prevent.clear');
    Route::get('/choosePlanPost', 'PostController@choosePlanPost')->name('ads.choosePlanPost');
    Route::get('/selectPlanPost/{planid}', 'PostController@selectplanpost')->name('ads.selectPlanPost');
    Route::get('/selectplanpostforplatinam/{planid}/{adUuid}', 'PostController@selectplanpostforplatinam')->name('ads.selectPlanPostPlatinam');
    Route::get('/post/create/{id}', 'PostController@attributes')->name('ads.post.attributes');
    Route::post('/post/add/', 'PostController@addpost')->name('ads.post.add');
    Route::post('/adspost/categories/','AdsController@getcategories')->name('ads.post.getcategories');
    Route::post('/adspost/subcategories/','AdsController@getsubcategories')->name('ads.post.getsubcategories');
    Route::post('/adspost/redirectPost/','PostController@redirectPost')->name('ads.post.redirectPost');
    Route::get('/adspost/tags/{id}','AdsController@getsubcategoriestags')->name('ads.post.getsubcategories_tags');
    Route::get('/search_cities', 'AdsController@autocomplete')->name('autocomplete_cities');
    Route::get('items/{id?}','AdsController@getads')->name('adsview');
    Route::post('/loadmoreitems', 'AdsController@loadmoresearch')->name('loadmoreitems');
    Route::post('/loadmoreitemsExtra', 'AdsController@getExtraFillter')->name('loadmoreitemsExtra');
    Route::post('/loadcategory', 'AdsController@loadcategory')->name('loadcategory');
    Route::post('/loadlocation', 'AdsController@loadlocation')->name('loadlocation');
    Route::post('item/','AdsController@getitems')->name('getitems');
    Route::get('item/{id}','AdsController@displayads')->name('adsview.getads');
    Route::get('/favads','AdsController@favouriteads')->name('favads');
    Route::get('/availableplatinamAd','AdsController@availableplatinamAd')->name('availableplatinamAd');

    Route::get('/buyPremium/{adUuid}', 'AdsController@buyPremium')->name('ads.buyPremium')->middleware('users');


    Route::post('/emailupdate', 'HomeController@ajaxemailupdate')->name('emailupdate');
    Route::post('/mobile', 'HomeController@ajaxotp')->name('mobile');
    Route::post('/mobile/check', 'HomeController@ajaxmobilecheck')->name('mobilecheck');
    /*Route::post('/registercheck', 'HomeController@registercheck')->name('registercheck');
    Route::post('/mobilereg', 'HomeController@mobile_verify_reg')->name('mobile_verify_reg');*/
    Route::post('/useravailable', 'HomeController@useravailable')->name('useravailable');
    Route::post('/emailregister', 'HomeController@emailregister')->name('emailregister');
    Route::post('/mobileregister', 'HomeController@mobileregister')->name('mobileregister');
    Route::get('/referral/{id}', 'HomeController@referralregister')->name('referral');
    Route::get('/mailstatus', 'HomeController@mailstatus')->name('mailstatus');
    
    Route::post('/reportusersubmit', 'HomeController@ajaxreportusersubmit')->name('reportusersubmit');
    Route::post('/reportadssubmit', 'HomeController@ajaxreportadssubmit')->name('reportadssubmit');

   
    Route::get('profile/info/{id}','ProfileController@displayuser')->name('view.profile');

    Route::get('profile/info-all/{id}','ProfileController@displayuserinfo')->name('view.profile.all')->middleware('admin');
    Route::get('profile/chathistory/{id}','ProfileController@chathistory')->name('chathistory')->middleware('admin');
    Route::post('profile/chathistory/getchatdata','ProfileController@getadmintouserdata')->name('chathistory.getadmintouserdata')->middleware('admin');
    Route::get('profile/chathistory/msg/{chatid}/{usrid}','ProfileController@adminsyncmessageview')->name('chathistory.adminsyncmessageview')->middleware('admin');
    Route::post('profile/chathistory/msg/get','ProfileController@AdminajaxSyncGetMessage')->name('chathistory.AdminajaxSyncGetMessage')->middleware('admin');

    Route::get('profile/notifications/{id}','ProfileController@notifications')->name('notifications')->middleware('admin');
    Route::get('profile/boughtpackages/{id}','ProfileController@boughtpackages')->name('boughtpackages')->middleware('admin');
    Route::get('profile/userbilling/{id}','ProfileController@userbilling')->name('userbilling')->middleware('admin');
    Route::get('profile/userwallets/{id}','ProfileController@userwallets')->name('userwallets')->middleware('admin');
    Route::post('users/wallet/Get', 'ManagementController@Ajaxuserwalletpassbook')->name('Ajaxuserwalletpassbook');
    Route::get('profile/userinvoices/{id}','ProfileController@userinvoices')->name('userinvoices')->middleware('admin');
    Route::get('profile/userblocked/{id}','ProfileController@userblocked')->name('userblocked')->middleware('admin');
    Route::post('user/invoice/Get', 'ProfileController@invoicedetails')->name('userads.invoice.get');
    Route::get('/invoicePdfdownload/{id}/{option}', 'ProfileController@invoicePdfdownload')->name('ads.invoicePdfdownload');
    Route::get('/profile/reviews/{id}', 'ProfileController@profileReviews')->name('profileReviews');
    //Route::post('profile/chathistory/', 'ManagementController@Ajaxuserwalletpassbook')->name('Ajaxuserwalletpassbook');

    
    Route::get('/follow','FollowersController@follow')->name('follow');
    Route::get('/blockuser','FollowersController@blockuser')->name('blockuser');
    //Route::get('/follow','FollowersController@follow')->name('follow');
    
    Route::get('/contact','ManagementController@contact')->name('contact');

    Route::post('/contactus/gettrack','ManagementController@gettrack')->name('contactus.gettrack');
    
    Route::patch('/contactus/{id}/reopen','ManagementController@trackreq_reopen')->name('track.reopen');
    Route::post('/contactus','ManagementController@contactus')->name('contactus');
    Route::get('/contact/ticket/{id}','ManagementController@ticket')->name('contact.ticket');
    Route::post('/contact/ticket/Sendmessage','ManagementController@ticketmessagesave')->name('contact.ticketmessagesave');

    Route::get('/faq', 'ManagementController@faqdata')->name('faq');
    Route::get('/faq/search', 'ManagementController@faqsearch')->name('faqqns');
    
    Route::get('/chat', 'ChatController@index')->name('chat');
    Route::get('/chat/user/{adid}/{sellerid}', 'ChatController@chat')->name('chatuser');
    Route::post('/chat/userdata', 'ChatController@getuserdata')->name('getchatuser');
    Route::post('/chat/SaveChat', 'ChatController@ajaxSaveMessage')->name('ajaxchatsave');
    Route::post('/chat/GetChat', 'ChatController@ajaxGetMessage')->name('ajaxchatget');
    Route::get('chat/{id}', 'ChatController@syncmessageview')->name('chats');
    Route::post('/chat/sync/SaveChat', 'ChatController@ajaxSyncSaveMessage')->name('ajaxchatSyncsave');
    Route::post('/chat/sync/GetChat', 'ChatController@ajaxSyncGetMessage')->name('ajaxchatSyncget');
    Route::post('/chat/readstatuschanges', 'ChatController@statuschaged')->name('chatreadstatuschaged');
    Route::post('/chat/deleteconversation', 'ChatController@deleteconversation')->name('chatdeleteconversation');

    Route::get('getfollwing/{uuid}', 'ManagementController@getfollwing')->name('getfollwing');
    Route::get('getfollowers/{uuid}', 'ManagementController@getfollowers')->name('getfollowers');   
    Route::get('/chat/seller/{uniqueidd}', 'ChatController@sellerchat')->name('sellerchat');

    


    /*Route::get('/sendwallettouser', 'ManagementController@sendwallettouser')->name('sendwallettouser');
    Route::post('/getuserdetails', 'ManagementController@getuserdetails')->name('getuserdetails');
    Route::post('/sendamounttofriends', 'ManagementController@sendamounttofriends')->name('sendamounttofriends');*/

    Route::post('/viewedads','AdsController@viewedads')->name('viewedads');
    //Route::get('/favads','AdsController@favouriteads')->name('favads');

    Route::get('clear_cache', 'ManagementController@clearCache');
    Route::post('/getcity', 'AdsController@getcity')->name('getcity');
    Route::post('showfads', 'AdsController@showfads')->name('showfads');
    //Route::get('/featureads/{id}', 'PlanController@featureads')->name('featureads');
    Route::get('/getlnglat', 'HomeController@getlanlat')->name('getlanlat');


    /*Route::get('event', 'OrderController@bookEvent');
    Route::post('payment', 'OrderController@eventOrderGen');
    Route::post('payment/status', 'OrderController@paymentCallback');
    Route::post('/payment/status', 'OrderController@paymentCallback');*/

    /*Route::get('event-registration', 'OrderController@register');
    Route::post('payment', 'OrderController@order');
    Route::post('payment/status', 'OrderController@paymentCallback');*/

    Route::get('event-registration', 'PaytmController@register');
    Route::post('payment', 'PaytmController@order');
    Route::post('payment/status', 'PaytmController@paymentCallback');
    //Route::get('payment/status', 'PaytmController@paytmerrormesg');
    Route::post('packagesbuypaytm', 'PaytmController@packagesbuypaytm');
    Route::post('packagepaymentsuccess', 'PaytmController@packagesbuypaytmCallback');
    Route::post('adspaytmpayment', 'PaytmController@adspaytmpayment');
    Route::post('adpostpaymentsuccess', 'PaytmController@adpostpaymentsuccessCallback');

    //buyplatinamplanforad
    Route::post('adspaytmpaymentforplatinam', 'PaytmController@adspaytmpaymentforplatinam');
    Route::post('adpostpaymentsuccessPlatinamCallback', 'PaytmController@adpostpaymentsuccessPlatinamCallback');

    Route::post('paidplanpayPlatinamsuccess', 'RazorpayController@paidPlanRazorPlatinamPaySuccess');
    //Route::post('packagespayPlatinamsuccess', 'RazorpayController@packageRazorPlatinamPaySuccess');

    Route::get('product', 'RazorpayController@index');
    Route::post('paysuccess', 'RazorpayController@razorPaySuccess');
    Route::post('paidplanpaysuccess', 'RazorpayController@paidPlanRazorPaySuccess');
    Route::post('packagespaysuccess', 'RazorpayController@packageRazorPaySuccess');
    Route::post('packagespaysuccesswithwallet', 'RazorpayController@packageRazorPaySuccessWithWallet');
    Route::get('razor-thank-you', 'RazorpayController@thankYou');
    Route::get('payment-canceled', 'RazorpayController@canceled');
    Route::post('getamt', 'RazorpayController@getamt');
    Route::post('getamtdetails', 'RazorpayController@getamtdetails');
    Route::post('getamtplatinam', 'RazorpayController@getamtplatinam');

    Route::post('walletpointused', 'RazorpayController@walletpointused');
    Route::post('walletpointusedforplatinam', 'RazorpayController@walletpointusedforplatinam');

    Route::post('walletpointusedproductpurchase', 'RazorpayController@walletpointusedproductpurchase');
    
    Route::get('wallet_record_export/{user_id}/{year}/{month}', 'ManagementController@walletexport')->name('wallet.export');
    
    Route::post('getbrandmodels', 'PostController@getbrandmodels');
    Route::post('walletusedwithrzrpayPlatinam', 'RazorpayController@walletusedwithrzrpayPlatinam');

    Route::post('walletwithplatinam', 'PaytmController@walletwithplatinam');
    Route::post('paymentCallbackWalletPlatinam', 'PaytmController@paymentCallbackWalletPlatinam');


    Route::get('export', 'OrderController@export')->name('export');
    Route::get('importExportView', 'OrderController@importExportView');
    Route::post('import', 'OrderController@import')->name('import');
    Route::post('getcities', 'PostController@getcities')->name('getcities');
    Route::post('getareas', 'PostController@getareas')->name('getareas');
    Route::post('getpincode', 'PostController@getpincode')->name('getpincode');
        

    Route::post('useronlinestatus', 'ProfileController@UserStatusCheck')->name('UserStatusCheck');
        
Route::group(['middleware'=>['auth','users']], function(){
    
    
    //Route::get('/home', 'HomeController@index')->name('dashboard');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile/disconnect/social', 'ProfileController@disconsocial')->name('profile.disconnectsocial');
    Route::get('/profile/referral', 'ProfileController@referraluser')->name('profile.referraluser');
    Route::post('/profile/update', 'ProfileController@profileupdate')->name('profile.update');
    Route::post('/profile/image/update', 'ProfileController@profileimageupdate')->name('profile.image.update');
    Route::get('/profile/mobile', 'ProfileController@mobile')->name('profile.mobile');
    Route::get('/profile/changepassword', 'ProfileController@changepassword')->name('profile.changepassword');
    Route::post('/profile/changepassword', 'ProfileController@updatepassword')->name('profile.updatepassword');
    Route::post('/profile/mobile/verify', 'ProfileController@mobileupdate')->name('profile.mobile.update');
    Route::post('/profile/mobile/check', 'ProfileController@Ajaxmobilecheck')->name('profile.mobile.check');
    Route::post('/profile/mobile/update', 'ProfileController@Ajaxmobileupdate')->name('profile.mobile.update.ajax');
    Route::get('/profile/mail', 'ProfileController@mail')->name('profile.mail');
    // Route::post('/profile/mail', 'ProfileController@mailupdate')->name('profile.mail.update');
    Route::post('/profile/mail/update/ajax', 'ProfileController@Ajaxmailupdate')->name('profile.mail.update.ajax');
    Route::get('/profile/mail/verify/{id}', 'ProfileController@mailverify')->name('profile.mail.verify');
    Route::get('/profile/workmail/', 'ProfileController@workmail')->name('profile.workmail');
    // Route::post('/profile/workmail/', 'ProfileController@workmailupdate')->name('profile.workmail.update');
    Route::post('/profile/workmail/update/ajax', 'ProfileController@Ajaxworkmailupdate')->name('profile.workmail.update.ajax');
    Route::get('/profile/workmail/verify/{id}', 'ProfileController@workmailverify')->name('profile.workmail.verify');
    Route::get('/profile/social/', 'ProfileController@social')->name('profile.social');
    Route::get('/profile/verify/social/{provider}', 'ProfileController@socialredirect')->name('profile.social.redirect');
    Route::get('/profile/govtproof', 'ProfileController@govtproof')->name('profile.govtproof');
    Route::post('/profile/govtproof/add', 'ProfileController@govtproofadd')->name('profile.govtproof.add');
    Route::get('/profile/govtproof/edit/{id}', 'ProfileController@govtproofedit')->name('profile.govtproof.edit');
    Route::post('/profile/govtproof/update', 'ProfileController@govtproofupdate')->name('profile.govtproof.update');
    Route::post('/profile/govtproof/delete/{id}', 'ProfileController@govtproofdelete')->name('profile.govtproof.delete');

    Route::get('/setting', 'SettingController@index')->name('setting');
    Route::get('/setting/privacy', 'SettingController@privacy')->name('setting.privacy');
    Route::post('/setting/privacy', 'SettingController@privacyupdate')->name('setting.privacy.update');
    Route::get('/setting/privacy/manage_user', 'SettingController@manageusers')->name('setting.privacy.manageusers');
    Route::post('/setting/privacy/manage_user/', 'SettingController@manageusersblock')->name('setting.privacy.manageusersblock');
    Route::post('/setting/privacy/manage_user_unblock/', 'SettingController@manageusersunblockchat')->name('setting.privacy.manageusersunblockchat');
    Route::post('/setting/privacy/manage_user/{block_user_id}', 'SettingController@manageusersunblock')->name('setting.privacy.manageusersunblock');
    Route::get('/setting/notification', 'SettingController@notification')->name('setting.notification');
    Route::post('/setting/notification', 'SettingController@notificationupdate')->name('setting.notification.update');
    Route::get('/setting/deactive', 'SettingController@deactiveUser')->name('setting.deactive');
    Route::post('/setting/deactive/', 'SettingController@deactiveUserconfirm')->name('setting.deactive.user');
    Route::get('/setting/logoutFeature', 'SettingController@logoutPage')->name('setting.logout');
    Route::post('/setting/logout/', 'SettingController@logoutsave')->name('setting.logout.all');
    Route::post('/setting/login/getdevice', 'SettingController@getlogindevice')->name('setting.login.getdevice');

    /*Route::get('/adspost', 'AdsController@index')->name('ads.post');*/
   /* Route::get('/adspost/categories/{id}','AdsController@getsubcategories')->name('ads.post.getsubcategories');
    Route::get('/adspost/tags/{id}','AdsController@getsubcategoriestags')->name('ads.post.getsubcategories_tags');
    Route::get('/search_cities', 'AdsController@autocomplete')->name('autocomplete_cities');*/
    //Route::post('/adspost/display/', 'AdsController@addpost')->name('ads.post.add');


    /*Route::get('/post', 'PostController@index')->name('ads.post');
    Route::get('/post/create/{id}', 'PostController@attributes')->name('ads.post.attributes');
    Route::post('/post/add/', 'PostController@addpost')->name('ads.post.add');*/

    Route::get('/user/ads', 'AdsController@userindex')->name('ads.user.index');
    Route::get('/user/ads/active/item', 'AdsController@activeadsget')->name('ads.user.active.get');
    Route::post('/user/ads/free/delete/', 'AdsController@adsdelete')->name('ads.user.free.delete');
    //Route::post('/user/ads/free/inactive/{id}', 'AdsController@adsinactive')->name('ads.user.free.inactive');
    Route::post('/user/ads/free/inactive/{id}', 'AdsController@inactive')->name('ads.user.free.inactive');
    Route::post('/user/ads/all/status/', 'AdsController@adssold')->name('ads.user.all.sold');
    Route::post('/user/ads/republish/', 'AdsController@republish')->name('ads.user.republish');
    Route::get('/user/ads/free/edit/{id}', 'AdsController@adsedit')->name('ads.user.free.edit');
    Route::post('/user/ads/free/update', 'AdsController@adsupdate')->name('ads.user.free.update');
    Route::get('/user/ads/inactive/item', 'AdsController@userinactiveadsget')->name('ads.user.inactive.get');
    Route::get('/user/ads/sold/item', 'AdsController@usersolditemsget')->name('ads.user.sold.get');
    Route::get('/user/ads/favourite/item', 'AdsController@userfavouriteitemsget')->name('ads.user.favourite.get');
    Route::get('/user/ads/promote', 'AdsController@promote')->name('ads.user.promote');
    Route::post('/user/ads/promote/save', 'AdsController@promotesave')->name('ads.user.promote.save');
    
    Route::get('/plans_REMOVED', 'PlanController@index')->name('plans');
    Route::get('/plans/{planid}/{adcount}/order', 'PlanController@order')->name('plans.order');
    //Route::get('/featureads/{id}', 'PlanController@featureads')->name('featureads');
    Route::get('/featureads/{planid}/{position}', 'PlanController@featureads')->name('featureads');
    Route::get('/featureads/order/{planid}/{days}', 'PlanController@featureadsorder')->name('featureads.order');
    Route::get('/showpackage/{auuid?}', 'PlanController@showpackage')->name('showpackage');
    Route::post('/showpackage/order', 'PlanController@showpackageOrder')->name('showpackageOrder');

    Route::post('/getaddress', 'AdsController@getaddress')->name('getaddress');
    
    Route::get('/bought_packages', 'BillingController@index')->name('ads.bought_packages');
    Route::get('/invoice', 'BillingController@invoice')->name('ads.invoice');
    Route::post('/invoice/Get', 'BillingController@invoicedetails')->name('ads.invoice.get');
    Route::get('/invoicePdf/{id}/{option}', 'BillingController@invoicePrintPDF')->name('ads.invoicePrintPDF');
    Route::get('/refundPdf/{id}/{option}', 'BillingController@refundPrintPDF')->name('ads.refundPrintPDF');
    Route::get('/billing', 'BillingController@billing')->name('ads.billing');
    Route::post('/billing/update', 'BillingController@billingUpdate')->name('ads.billing.update');

    Route::get('/wallet', 'ManagementController@walletpassbook')->name('usertransaction');
    Route::post('/wallet/Get', 'ManagementController@Ajaxwalletpassbook')->name('ajaxusertransaction');
    Route::get('/wallet/redeem', 'ManagementController@walletredeem')->name('userredeem');
    Route::post('/wallet/redeem/Get', 'ManagementController@Ajaxwalletredeem')->name('ajaxuserreddem');
    Route::post('/walletpoints', 'ManagementController@userwalletpoint')->name('walletpoint');
    Route::post('/redeemamount', 'ManagementController@redeemamount')->name('redeemamount');
    Route::post('/checkingRedeem', 'ManagementController@redeemamountchecking')->name('redeemamountchecking');

    Route::get('notification', 'PagesController@notification')->name('notification');
    Route::post('notification/delete/{id}', 'PagesController@notification_delete')->name('noti.delete');
    Route::post('notification/deleteall', 'PagesController@notification_deleteall')->name('noti.deleteall');
    Route::post('/ratinguser', 'ProfileController@ratinguser')->name('ratinguser');
    Route::post('/adsreviews', 'AdsController@adsreviews')->name('adsreviews');
    Route::post('/Userajaxreview', 'ProfileController@Userajaxreview')->name('Userajaxreview');
    Route::post('/planadslist/view', 'BillingController@planadslist')->name('viewplanadslist');

    
    Route::post('/makeAnOffer', 'AdsController@makeAnOffer')->name('makeAnOffer');
    
    Route::post('/checkpanwalletamt', 'PlanController@checkpanwalletamt')->name('checkpanwalletamt');
    
    Route::post('getavailablefreeads', 'HomeController@getavailablefreeads')->name('getavailablefreeads');

    Route::post('shownotificationicon', 'HomeController@shownotificationicon')->name('shownotificationicon');


       

});
        
        

Route::post('admin/planadslist/view', 'BillingController@adminviewplanadslist')->name('adminviewplanadslist');
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin'],], function(){

    Route::get('role_access/role', 'UserController@listroles')->name('admin.listroles')->middleware('checkroleaccess:22,view_all');
    Route::get('role_access/addrole', 'UserController@addrole')->name('admin.role.add')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/addrole', 'UserController@saverole')->name('admin.role.save')->middleware('checkroleaccess:22,allow_all');
    Route::get('role_access/editrole/{ruuid}', 'UserController@editrole')->name('admin.role.edit')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/deleterole', 'UserController@deleterole')->name('admin.role.delete')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/updaterole', 'UserController@updaterole')->name('admin.role.update')->middleware('checkroleaccess:22,allow_all');
    Route::get('role_access/adminusers', 'UserController@adminusers')->name('admin.adminusers')->middleware('checkroleaccess:22,view_all');
    Route::get('role_access/addsubadmin', 'UserController@addsubadmin')->name('admin.addsubadmin')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/status_subadmin', 'UserController@status_subadmin')->name('admin.status_subadmin')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/addsubadmin', 'UserController@savesubadmin')->name('admin.savesubadmin')->middleware('checkroleaccess:22,allow_all');
    Route::get('role_access/edit_subadmin/{uuuid}', 'UserController@edit_subadmin')->name('admin.edit_subadmin')->middleware('checkroleaccess:22,allow_all');
    Route::post('role_access/updatesubadmin', 'UserController@updatesubadmin')->name('admin.updatesubadmin')->middleware('checkroleaccess:22,allow_all');

	Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::get('getunverifieddetails', 'DashboardController@getunverifieddetails')->name('admin.getunverifieddetails');
    Route::get('icons', 'DashboardController@icon')->name('admin.icons');
	Route::get('log', 'DashboardController@log')->name('admin.log')->middleware('checkroleaccess:12,allow_all');
	
    Route::get('settings/sites', 'DashboardController@setting')->name('admin.setting')->middleware('checkroleaccess:8,view_all');
	Route::post('settings/sites', 'DashboardController@settingstore')->name('admin.settings.store')->middleware('checkroleaccess:8,allow_all');
	Route::get('settings/points', 'DashboardController@point')->name('admin.point')->middleware('checkroleaccess:10,view_all');
	Route::post('settings/points', 'DashboardController@pointstore')->name('admin.point.store')->middleware('checkroleaccess:10,allow_all');
	Route::get('settings/change_password','DashboardController@changePassword')->name('admin.changepassword');
    Route::post('settings/change_password','DashboardController@changePasswordUpdate')->name('admin.changepassword.update');
    Route::get('settings/profile','DashboardController@profile')->name('admin.profile');
    Route::post('settings/profile','DashboardController@profileUpdate')->name('admin.profile.update');

    Route::post('settings/paytm', 'DashboardController@settingpaytm')->name('admin.settings.paytm')->middleware('checkroleaccess:8,allow_all');
    Route::post('settings/razorpay', 'DashboardController@settingrazorpay')->name('admin.settings.razorpay')->middleware('checkroleaccess:8,allow_all');
    Route::get('settings/payment', 'DashboardController@payment')->name('admin.payment')->middleware('checkroleaccess:8,allow_all');
    Route::post('settings/payment', 'DashboardController@settingChooseGateway')->name('admin.settings.settingChooseGateway')->middleware('checkroleaccess:8,allow_all');

    Route::get('management/faq', 'ManagementController@faq')->name('admin.management')->middleware('checkroleaccess:5,view_all');
    Route::get('management/faq/questions', 'ManagementController@faqquestion')->name('admin.faq.faqquestion')->middleware('checkroleaccess:5,allow_all');
    Route::post('management/faq/answer', 'ManagementController@insert')->name('admin.faq.answer')->middleware('checkroleaccess:5,allow_all');      
    Route::get('faq/{id}/view', 'ManagementController@view')->name('admin.faq.view')->middleware('checkroleaccess:5,view_all');
    Route::post('faq/get', 'ManagementController@getfaq')->name('admin.faq.get')->middleware('checkroleaccess:5,view_all');
    Route::get('management/faq/edit/{id}', 'ManagementController@editfaq')->name('admin.faq.editfaq')->middleware('checkroleaccess:5,view_all');
    Route::post('faq/update', 'ManagementController@updatefaq')->name('admin.faq.update')->middleware('checkroleaccess:5,allow_all');
    Route::post('faq/delete/{id}', 'ManagementController@deletefaq')->name('admin.faq.delete')->middleware('checkroleaccess:5,allow_all');
    Route::post('management/upload', 'ManagementController@upload')->name('admin.management.upload')->middleware('checkroleaccess:5,view_all');


    Route::post('users/proof_verification/unverifieddata','ProofController@getunverified')->name('admin.users.proofunverifydata')->middleware('checkroleaccess:11,view_all');
    Route::post('users/proof_verification/verifieddata','ProofController@getverified')->name('admin.users.proofverifydata')->middleware('checkroleaccess:11,view_all');
    Route::get('users/proof_verification','ProofController@index')->name('admin.users.proofverify')->middleware('checkroleaccess:11,view_all');
    Route::get('users/proof_verification/{proofid}','ProofController@view')->name('admin.users.proofview')->middleware('checkroleaccess:11,allow_all');
    Route::Post('users/proof_verification/verified/{proofid}','ProofController@verified')->name('admin.users.proofverified')->middleware('checkroleaccess:11,allow_all');
    Route::post('users/proof_verification/destroy/','ProofController@delete')->name('admin.users.proofdelete')->middleware('checkroleaccess:11,allow_all');
    Route::post('users/proof_verification/recheck/{id}','ProofController@recheck')->name('admin.users.proofrecheck')->middleware('checkroleaccess:11,allow_all');

    Route::get('ads/categories/treeview', 'CategoriesController@treeview')->name('admin.ads.categories.treeview')->middleware('checkroleaccess:13,view_all');
    Route::get('ads/categories', 'CategoriesController@index')->name('admin.ads.categories')->middleware('checkroleaccess:13,view_all');
    Route::get('ads/categories/create', 'CategoriesController@createcategories')->name('admin.ads.createcategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/categories/add', 'CategoriesController@addcategories')->name('admin.ads.addcategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/categories/', 'CategoriesController@parentcategoriesupdate')->name('admin.ads.updatecategories')->middleware('checkroleaccess:13,allow_all');
    Route::get('ads/categories/view', 'CategoriesController@getparentcategories')->name('admin.ads.viewcategories')->middleware('checkroleaccess:13,view_all');
    //Route::post('ads/categories/delete/{id}', 'CategoriesController@parentcategoriesdelete')->name('admin.ads.deletecategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/categories/status/{id}', 'CategoriesController@parentcategoriesstatus')->name('admin.ads.statuscategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/categories/delete/{id}', 'CategoriesController@parentcategoriesdelete')->name('admin.ads.deletecategories')->middleware('checkroleaccess:13,allow_all');
    Route::get('ads/categories/edit/{id}', 'CategoriesController@parentcategoriesedit')->name('admin.ads.editcategories')->middleware('checkroleaccess:13,view_all');
    
    Route::get('ads/sub_categories/{id}', 'CategoriesController@subindex')->name('admin.ads.subcategories')->middleware('checkroleaccess:13,view_all');
    Route::get('ads/sub_categories/view/{id}', 'CategoriesController@getsubcategories')->name('admin.ads.viewsubcategories')->middleware('checkroleaccess:13,view_all');
    Route::get('ads/sub_categories/create/{id}', 'CategoriesController@createsubcategories')->name('admin.ads.createsubcategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/sub_categories/add', 'CategoriesController@addsubcategories')->name('admin.ads.addsubcategories');
    Route::post('ads/sub_categories/status/{id}', 'CategoriesController@subcategoriesstatus')->name('admin.ads.statussubcategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/sub_categories/delete/{id}', 'CategoriesController@subcategoriesdelete')->name('admin.ads.deletesubcategories')->middleware('checkroleaccess:13,allow_all');
    Route::get('ads/sub_categories/edit/{parentid}/{id}', 'CategoriesController@subcategoriesedit')->name('admin.ads.editsubcategories')->middleware('checkroleaccess:13,allow_all');
    Route::post('ads/Sub_categories/', 'CategoriesController@subcategoriesupdate')->name('admin.ads.updatesubcategories')->middleware('checkroleaccess:13,allow_all');
    
    Route::get('location/treeview', 'LocationController@treeview')->name('admin.location.treeview')->middleware('checkroleaccess:19,view_all');
    Route::get('location/country', 'LocationController@index')->name('admin.location.country')->middleware('checkroleaccess:19,view_all');
    Route::post('location/country/view', 'LocationController@getcountry')->name('admin.location.viewcountry')->middleware('checkroleaccess:19,view_all');
    Route::get('location/country/create', 'LocationController@createcountry')->name('admin.location.createcountry')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/country/add', 'LocationController@addcountry')->name('admin.location.addcountry')->middleware('checkroleaccess:19,allow_all');
    Route::get('location/country/edit/{id}', 'LocationController@countryedit')->name('admin.location.editcountry')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/country/status/{id}', 'LocationController@countrystatus')->name('admin.location.statuscountry')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/country', 'LocationController@countryupdate')->name('admin.location.updatecountry')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/country/delete/{id}', 'LocationController@countrydelete')->name('admin.location.deletecountry')->middleware('checkroleaccess:19,allow_all');

    Route::get('location/state/{id}', 'LocationController@stateindex')->name('admin.location.state')->middleware('checkroleaccess:19,view_all');
    Route::get('location/state/view/{id}', 'LocationController@getstate')->name('admin.location.viewstate')->middleware('checkroleaccess:19,view_all');
    Route::get('location/state/create/{id}', 'LocationController@createstate')->name('admin.location.createstate')->middleware('checkroleaccess:19,view_all');
    Route::post('location/state/add', 'LocationController@addstate')->name('admin.location.addstate')->middleware('checkroleaccess:19,allow_all');
    Route::get('location/state/edit/{countryid}/{id}', 'LocationController@stateedit')->name('admin.location.editstate')->middleware('checkroleaccess:19,view_all');
    Route::post('location/state/', 'LocationController@stateupdate')->name('admin.location.updatestate')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/state/delete/{id}', 'LocationController@statedelete')->name('admin.location.deletestate')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/state/status/{id}', 'LocationController@statestatus')->name('admin.location.statusstate')->middleware('checkroleaccess:19,allow_all');

    Route::get('location/cities/{id}', 'LocationController@citiesindex')->name('admin.location.cities')->middleware('checkroleaccess:19,view_all');
    Route::get('location/cities/view/{id}', 'LocationController@getcities')->name('admin.location.viewcities')->middleware('checkroleaccess:19,view_all');
    Route::get('location/cities/create/{id}', 'LocationController@createcities')->name('admin.location.createcities')->middleware('checkroleaccess:19,view_all');
    Route::post('location/cities/add', 'LocationController@addcities')->name('admin.location.addcities')->middleware('checkroleaccess:19,allow_all');
    Route::get('location/cities/edit/{stateid}/{id}', 'LocationController@citiesedit')->name('admin.location.editcities')->middleware('checkroleaccess:19,view_all');
    Route::post('location/cities/', 'LocationController@citiesupdate')->name('admin.location.updatecities')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/cities/delete/{id}', 'LocationController@citiesdelete')->name('admin.location.deletecities')->middleware('checkroleaccess:19,allow_all');
    Route::post('location/cities/status/{id}', 'LocationController@citiesstatus')->name('admin.location.statuscities')->middleware('checkroleaccess:19,allow_all');

    Route::get('location/request', 'LocationController@locationrequest')->name('admin.location.request');
    Route::post('location/request/autocomplete', 'LocationController@getrequest')->name('admin.location.autocomplete');
    Route::get('location/request/view/{id}', 'LocationController@locationview')->name('admin.location.viewrequest');
    Route::post('location/request/add', 'LocationController@locationadd')->name('admin.location.addrequest');
    Route::get('location/request/country', 'LocationController@autocompletecountry')->name('admin.location.autocompletecountry');
    Route::get('location/request/state/{id}', 'LocationController@autocompletestate')->name('admin.location.autocompletestate');

    Route::get('plans', 'PlanController@index')->name('admin.plans')->middleware('checkroleaccess:3,allow_all');
    Route::Post('plans/view', 'PlanController@getplans')->name('admin.plans.view')->middleware('checkroleaccess:3,view_all');
    Route::post('plans/delete/{id}', 'PlanController@plansdelete')->name('admin.plans.delete')->middleware('checkroleaccess:3,allow_all');
    Route::post('plans/status/{id}', 'PlanController@plansstatus')->name('admin.plans.status')->middleware('checkroleaccess:3,allow_all');
    Route::get('plans/create', 'PlanController@createplans')->name('admin.plans.create')->middleware('checkroleaccess:3,allow_all');
    Route::post('plans/add', 'PlanController@addplans')->name('admin.plans.add')->middleware('checkroleaccess:3,allow_all');
    Route::get('plans/edit/{id}', 'PlanController@editplans')->name('admin.plans.edit')->middleware('checkroleaccess:3,allow_all');
    Route::post('plans/update', 'PlanController@updateplans')->name('admin.plans.update')->middleware('checkroleaccess:3,allow_all');
    Route::get('plans/history', 'PlanController@history')->name('admin.plans.history')->middleware('checkroleaccess:3,allow_all');
    Route::post('plans/history/view', 'PlanController@historyview')->name('admin.plans.historyview')->middleware('checkroleaccess:3,view_all');

    
    Route::get('premiumadsplans', 'PlanController@premiumAdsPlan')->name('admin.premiumadsplan')->middleware('checkroleaccess:3,view_all');
    Route::get('premiumadsplans/create', 'PlanController@premiumAdsPlanCreate')->name('admin.premiumadsplans.create')->middleware('checkroleaccess:3,allow_all');
    Route::post('premiumadsplans/add', 'PlanController@addpremiumAdsPlan')->name('admin.premiumadsplans.add')->middleware('checkroleaccess:3,allow_all');
    Route::post('premiumadsplans/view','PlanController@getPremiumPlans')->name('admin.getPremiumPlans')->middleware('checkroleaccess:3,view_all');
    Route::get('premiumadsplans/edit/{id}', 'PlanController@premiumPlansEdit')->name('admin.premiumadsplans.edit')->middleware('checkroleaccess:3,allow_all');
    Route::post('premiumadsplans/update', 'PlanController@premiumPlansUpdate')->name('admin.premiumadsplans.update')->middleware('checkroleaccess:3,allow_all');
    Route::post('premiumadsplans/status/{id}', 'PlanController@premiumPlansStatus')->name('admin.premiumadsplans.status')->middleware('checkroleaccess:3,allow_all');


    Route::get('paidadsplans', 'PlanController@paidAdsPlan')->name('admin.paidadsplan')->middleware('checkroleaccess:3,view_all');
    Route::get('paidadsplans/create', 'PlanController@paidAdsPlanCreate')->name('admin.paidadsplan.create')->middleware('checkroleaccess:3,allow_all');
    Route::post('paidadsplans/add', 'PlanController@addPaidAdsPlan')->name('admin.paidadsplans.add')->middleware('checkroleaccess:3,allow_all');
    Route::post('paidadsplansview','PlanController@getPaidPlans')->name('admin.getPaidPlans')->middleware('checkroleaccess:3,view_all');
    Route::Post('paidplans/view', 'PlanController@getPaidPlansDetail')->name('admin.plans.view')->middleware('checkroleaccess:3,view_all');
    Route::post('paidplans/status/{id}', 'PlanController@paidPlansStatus')->name('admin.plans.paidplanstatus')->middleware('checkroleaccess:3,allow_all');
    Route::get('paidplans/edit/{id}', 'PlanController@paidPlansEdit')->name('admin.paidplans.edit')->middleware('checkroleaccess:3,allow_all');
    Route::post('paidplans/update', 'PlanController@paidPlansUpdate')->name('admin.paidplans.update')->middleware('checkroleaccess:3,allow_all');
     
     Route::get('topadsplans', 'PlanController@topAdsPlan')->name('admin.topadsplan')->middleware('checkroleaccess:3,view_all');
     Route::get('topadsplans/create', 'PlanController@topAdsPlanCreate')->name('admin.topadsplan.create')->middleware('checkroleaccess:3,allow_all');
     Route::post('topadsplans/add', 'PlanController@addTopAdsPlan')->name('admin.topadsplans.add')->middleware('checkroleaccess:3,allow_all');
     Route::post('topplansview','PlanController@getTopPlans')->name('admin.getTopPlans')->middleware('checkroleaccess:3,view_all');
      Route::post('topplans/status/{id}', 'PlanController@topPlansStatus')->name('admin.plans.topplanstatus')->middleware('checkroleaccess:3,allow_all');
      Route::post('topplans/update', 'PlanController@topPlansUpdate')->name('admin.topplans.update')->middleware('checkroleaccess:3,allow_all');
      Route::get('topplans/edit/{id}', 'PlanController@topPlansEdit')->name('admin.topplans.edit')->middleware('checkroleaccess:3,view_all');

    Route::get('customField', 'CustomfieldController@index')->name('admin.customfield')->middleware('checkroleaccess:14,view_all');
    Route::get('customField/create', 'CustomfieldController@createfield')->name('admin.customfield.create')->middleware('checkroleaccess:14,allow_all');
    Route::post('customField/add', 'CustomfieldController@Addfield')->name('admin.customfield.add')->middleware('checkroleaccess:14,allow_all');
    Route::get('customField/view', 'CustomfieldController@getfield')->name('admin.customfield.view')->middleware('checkroleaccess:14,view_all'); 
    Route::post('customField/status/{id}', 'CustomfieldController@fieldstatus')->name('admin.customfield.status')->middleware('checkroleaccess:14,allow_all');
    Route::post('customField/delete/{id}', 'CustomfieldController@fielddelete')->name('admin.customfield.delete')->middleware('checkroleaccess:14,allow_all');
    Route::get('customField/edit/{id}', 'CustomfieldController@fieldedit')->name('admin.customfield.edit')->middleware('checkroleaccess:14,allow_all');
    Route::post('customField/update', 'CustomfieldController@fieldupdate')->name('admin.customfield.update')->middleware('checkroleaccess:14,allow_all');

    Route::get('customField/{id}/options', 'CustomfieldController@option')->name('admin.customfield.option')->middleware('checkroleaccess:14,allow_all');
    Route::get('customField/{id}/options/create', 'CustomfieldController@createoption')->name('admin.customfield.option.create')->middleware('checkroleaccess:14,allow_all');
    Route::post('customField/options/add', 'CustomfieldController@Addoption')->name('admin.customfield.option.add')->middleware('checkroleaccess:14,allow_all');
    Route::get('customField/options/view/{id}', 'CustomfieldController@getoption')->name('admin.customfield.option.view')->middleware('checkroleaccess:14,view_all');
    Route::post('customField/options/delete/{id}', 'CustomfieldController@optiondelete')->name('admin.customfield.option.delete')->middleware('checkroleaccess:14,allow_all');
    Route::get('customField/{fieldid}/{id}/options/edit/', 'CustomfieldController@optionedit')->name('admin.customfield.option.edit')->middleware('checkroleaccess:14,allow_all');
    Route::post('customField/options/update/', 'CustomfieldController@optionupdate')->name('admin.customfield.option.update')->middleware('checkroleaccess:14,allow_all');

    Route::get('ads/sub_categories/{id}/Customfield', 'CategoriesController@customfield')->name('admin.ads.customfield');
    Route::get('ads/sub_categories/{id}/Customfield/create', 'CategoriesController@createfield')->name('admin.ads.customfield.create');
    Route::get('ads/sub_categories/customfield/view/{id}', 'CategoriesController@getfield')->name('admin.ads.customfield.view');
    Route::post('ads/sub_categories/customfield/add', 'CategoriesController@addfield')->name('admin.ads.customfield.add');
    Route::post('ads/sub_categories/customfield/delete/{id}', 'CategoriesController@fielddelete')->name('admin.ads.customfield.delete');
    Route::get('ads/sub_categories/{subid}/{id}/customfield/edit', 'CategoriesController@fieldedit')->name('admin.ads.customfield.edit');
    Route::post('ads/sub_categories/customfield/update/', 'CategoriesController@fieldupdate')->name('admin.ads.customfield.update');

    Route::post('ads/sub_categories/brandModels/add/', 'CategoriesController@brandModelsAdd')->name('admin.ads.brandModels.add');
    Route::post('ads/sub_categories/customfield/AddValues/', 'CategoriesController@brandModelsAddValues')->name('admin.ads.brandModelsValues.add');
    Route::get('ads/sub_categories/{id}/brandModels', 'CategoriesController@brandModels')->name('admin.ads.brandModels');
    Route::post('ads/sub_categories/getbrandmodels/view', 'CategoriesController@getbrandmodels')->name('admin.ads.getbrandmodels.view');

    Route::get('ads/adsdata','AdsController@index')->name('admin.ads')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/verification/get/','AdsController@adsverification')->name('admin.ads.adsdata')->middleware('checkroleaccess:2,view_all');
    Route::get('ads/adsdata/{id}','AdsController@view')->name('admin.ads.view')->middleware('checkroleaccess:2,view_all');
    Route::Post('ads/adsdata/verified/{id}','AdsController@verified')->name('admin.ads.verified')->middleware('checkroleaccess:2,allow_all');
    Route::Post('ads/adsdata/reject/','AdsController@reject')->name('admin.ads.reject')->middleware('checkroleaccess:2,allow_all');
    Route::Post('ads/adsdata/addvalidity/','AdsController@addvalidity')->name('admin.ads.addvalidity')->middleware('checkroleaccess:2,allow_all');
    Route::get('ads/adsdata/edit/{id}','AdsController@edit')->name('admin.ads.edit')->middleware('checkroleaccess:2,allow_all');
    Route::Post('ads/adsdata/save/','AdsController@save')->name('admin.ads.save')->middleware('checkroleaccess:2,allow_all');
    Route::Post('ads/adsdata/block/{id}','AdsController@block')->name('admin.ads.block')->middleware('checkroleaccess:2,allow_all');

    Route::get('ads/feature','AdsController@featureAdsList')->name('admin.featureads')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/feature/view','AdsController@getfeatureads')->name('admin.getfeatureadsList')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/feature/expire','AdsController@featureAdsListRemove')->name('admin.featureAdsListRemove')->middleware('checkroleaccess:2,allow_all');

    Route::get('ads/topAdslist','AdsController@topAdsList')->name('admin.topadslist')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/topAdslist/view','AdsController@gettopAdsListads')->name('admin.gettopadsList')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/topAdslist/expire','AdsController@topAdsListRemove')->name('admin.topAdsListRemove')->middleware('checkroleaccess:2,allow_all');
    
    Route::get('ads/pearlAdslist','AdsController@pearlAdsList')->name('admin.pearladslist')->middleware('checkroleaccess:2,view_all');
    Route::post('ads/pearlAdslist/view','AdsController@getpearlAdsListads')->name('admin.getpearladsList')->middleware('checkroleaccess:2,allow_all');
    Route::post('ads/pearlAdslist/expire','AdsController@pearlAdsListRemove')->name('admin.pearlAdsListRemove')->middleware('checkroleaccess:2,allow_all');


    Route::get('user/details', 'UserController@index')->name('admin.users')->middleware('checkroleaccess:4,view_all');
    Route::post('user/get', 'UserController@getuser')->name('admin.users.get')->middleware('checkroleaccess:4,view_all');
    Route::post('user/UnVerifiedDelete', 'UserController@unverifieddelete')->name('admin.users.unverifieddelete')->middleware('checkroleaccess:4,allow_all');
    Route::get('user/details/{id}/view', 'UserController@view')->name('admin.users.view')->middleware('checkroleaccess:4,view_all');

    Route::Post('user/details/addwallet','UserController@adminaddwallet')->name('admin.user.adminaddwallet')->middleware('checkroleaccess:4,view_all');

    Route::Post('user/details/deductwallet','UserController@admindeductwallet')->name('admin.user.admindeductwallet')->middleware('checkroleaccess:4,view_all');
    
    Route::post('user/block', 'UserController@block')->name('admin.users.block')->middleware('checkroleaccess:4,allow_all');
    Route::post('user/unblock', 'UserController@unblock')->name('admin.users.unblock')->middleware('checkroleaccess:4,allow_all');
    Route::post('user/deleteuser', 'UserController@deleteuser')->name('admin.users.deleteuser')->middleware('checkroleaccess:4,allow_all');

    Route::get('user/profilephoto', 'UserController@profilephoto')->name('admin.users.profilephoto')->middleware('checkroleaccess:17,view_all');
    Route::post('user/profilephoto/get', 'UserController@getuserphoto')->name('admin.users.profilephotoget')->middleware('checkroleaccess:17,view_all');
    Route::get('user/profilephoto/{id}/view', 'UserController@viewphoto')->name('admin.users.profilephotoview')->middleware('checkroleaccess:17,view_all');
     Route::post('user/profilephoto/verify', 'UserController@photoverify')->name('admin.users.photoverify')->middleware('checkroleaccess:17,allow_all');
    Route::post('user/profilephoto/unverify', 'UserController@photounverify')->name('admin.users.photounverify')->middleware('checkroleaccess:17,allow_all');

    Route::get('complaints/user', 'ComplaintsController@user')->name('admin.complaint.users')->middleware('checkroleaccess:6,view_all');
    Route::post('complaints/user/get', 'ComplaintsController@getusercomplaint')->name('admin.complaint.users.get')->middleware('checkroleaccess:6,view_all');
    Route::get('complaints/ads', 'ComplaintsController@ads')->name('admin.complaint.ads')->middleware('checkroleaccess:7,view_all');
    Route::post('complaints/ads/get', 'ComplaintsController@getadscomplaint')->name('admin.complaint.ads.get')->middleware('checkroleaccess:7,view_all');
    Route::post('getcomplaintdetails', 'ComplaintsController@getcomplaintdetails')->name('getcomplaintdetails')->middleware('checkroleaccess:6,allow_all');
    Route::post('getcomplaintuserdetails', 'ComplaintsController@getcomplaintuserdetails')->name('getcomplaintuserdetails')->middleware('checkroleaccess:6,allow_all');
    Route::post('adminchattouser', 'ComplaintsController@adminchattouser')->name('adminchattouser')->middleware('checkroleaccess:6,allow_all');
    Route::get('complaints/chat', 'ChathistoryController@chat')->name('admin.complaint.chat')->middleware('checkroleaccess:6,view_all');
    Route::post('complaints/chat/get', 'ChathistoryController@getcomplaintchat')->name('admin.complaint.chat.get')->middleware('checkroleaccess:6,view_all');
    Route::get('complaints/chat/message/{id}', 'ChathistoryController@chatmessage')->name('admin.complaint.chatmessage')->middleware('checkroleaccess:6,view_all');
    Route::post('complaints/chat/message/save', 'ChathistoryController@chatmessagesave')->name('admin.complaint.chatmessage.save')->middleware('checkroleaccess:6,view_all');

    Route::get('wallets', 'WalletController@index')->name('wallets')->middleware('checkroleaccess:9,view_all');
    Route::Post('wallets/create', 'WalletController@store')->name('admin.wallets.create')->middleware('checkroleaccess:9,allow_all');

    Route::get('/report/redeemamount', 'ReportController@reportRedeem')->name('admin.reportRedeem')->middleware('checkroleaccess:15,view_all');
    Route::post('/report/redeemamount/get', 'ReportController@getRedeem')->name('admin.reportRedeem.get')->middleware('checkroleaccess:15,view_all');


    Route::get('/report/planpurchase', 'ReportController@planpurchases')->name('admin.planpurchases')->middleware('checkroleaccess:18,view_all');
    Route::post('/report/planpurchase/get', 'ReportController@getplanpurchases')->name('admin.getplanpurchases')->middleware('checkroleaccess:18,view_all');
    Route::get('/report/purchaserefund/{puuid}', 'ReportController@purchaserefund')->name('admin.purchaserefund')->middleware('checkroleaccess:18,allow_all');
    Route::get('/report/refund_details/{refundid}', 'ReportController@refund_details')->name('admin.refund_details')->middleware('checkroleaccess:18,allow_all');
    Route::post('/report/refund_plans', 'ReportController@refund_plans')->name('admin.refund_plans')->middleware('checkroleaccess:18,allow_all');

    
    Route::post('user/sendmail', 'UserController@usermail')->name('admin.usermail');

    Route::get('/chathistory', 'ChathistoryController@index')->name('admin.chathistory');

    Route::get('/report/contactus', 'ReportController@reportContactus')->name('admin.reportContactus')->middleware('checkroleaccess:16,view_all');
    Route::post('/report/contactus/get', 'ReportController@getContactus')->name('admin.reportContactus.get')->middleware('checkroleaccess:16,view_all');

     Route::post('/report/contactus/statuscomplete', 'ReportController@getCompleteContactus')->name('admin.reportContactus.statuscomplete')->middleware('checkroleaccess:16,view_all');

    Route::get('/report/contactus/chat/{id}', 'ReportController@reportContactusChat')->name('admin.reportContactus.chat')->middleware('checkroleaccess:16,view_all');
    Route::post('/report/contactus/chat', 'ReportController@reportContactusChatSave')->name('admin.reportContactus.chat.save')->middleware('checkroleaccess:16,allow_all');
    Route::post('/report/markascomplete', 'ReportController@markascomplete')->name('admin.reportContactus.markascomplete')->middleware('checkroleaccess:16,allow_all');
    

    Route::get('/report/reportBillInfo', 'ReportController@reportBillInfo')->name('admin.reportBillInfo')->middleware('checkroleaccess:20,view_all');
    Route::post('/report/reportBillInfo/get', 'ReportController@getreportBillInfo')->name('admin.reportBillInfo.get')->middleware('checkroleaccess:20,view_all');
    Route::get('/report/reportBillInfo/view/{id}', 'ReportController@reportBillInfoview')->name('admin.reportBillInfo.view')->middleware('checkroleaccess:20,view_all');
    Route::post('/report/reportBillInfo/save', 'ReportController@reportBillInfosave')->name('admin.reportBillInfo.save')->middleware('checkroleaccess:20,allow_all');

    Route::get('/broadcast', 'ManagementController@broadcast')->name('admin.broadcast')->middleware('checkroleaccess:21,view_all');
    Route::post('/broadcast/details', 'ManagementController@getbroadcastdetails')->name('admin.broadcast.getbroadcastdetails')->middleware('checkroleaccess:21,view_all');
    Route::post('/broadcast/send', 'ManagementController@broadcastSend')->name('admin.broadcast.send')->middleware('checkroleaccess:21,allow_all');
    Route::get('/broadcast/Edit/{id}', 'ManagementController@broadcastEdit')->name('admin.broadcast.edit')->middleware('checkroleaccess:21,allow_all');
    Route::post('/broadcast/update', 'ManagementController@broadcastUpdate')->name('admin.broadcast.update')->middleware('checkroleaccess:21,allow_all');
    Route::post('/broadcast/delete/{id}', 'ManagementController@delete')->name('admin.broadcast.delete')->middleware('checkroleaccess:21,allow_all');
    
    Route::get('/read_notification/{id}', 'DashboardController@read_notification')->name('admin.read_notification')->middleware('checkroleaccess:23,allow_all');

});