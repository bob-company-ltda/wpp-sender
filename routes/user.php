<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User as USER;
use App\Includes as ADDONS;

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth','user','saas']], function (){
   
   //all dashboard routes
   
   
   Route::get('dashboard2',                       [USER\DashboardController::class, 'index'])->name('dashboard.index');
   Route::get('dashboard',                       [USER\DashboardController::class, 'index2'])->name('dashboard2.index');
   Route::get('dashboard-static-data',           [USER\DashboardController::class, 'dashboardData'])->name('dashboard.static');
   Route::get('messages-transaction/{days}',     [USER\DashboardController::class, 'getMessagesTransaction'])->name('messages.static');
   Route::get('chatbot-transaction/{days}',      [USER\DashboardController::class, 'getChatbotTransaction'])->name('chatbot.static');
   Route::get('bulk-transaction/{days}',        [USER\DashboardController::class, 'getBulkTransaction'])->name('bulk.static');
   Route::get('single-transaction/{days}',      [USER\DashboardController::class, 'getSingleTransaction'])->name('single.static');
   Route::get('messages-types-transaction/{days}', [USER\DashboardController::class, 'messagesStatics'])->name('types.static');

   //device routes
   Route::resource('cloudapi',                    USER\CloudApiController::class);
   Route::get('cloudapi/{id}/cloud',                 [USER\CloudApiController::class,'cloudApi'])->name('cloudapi.hook');
   Route::post('check-session/{id}',            [USER\CloudApiController::class,'checkSession']);
   Route::post('/logout-session/{id}',          [USER\CloudApiController::class,'logoutSession']);
   Route::post('/cloudapi-statics',               [USER\CloudApiController::class,'cloudapiStatics']);
  
   Route::get('/cloudapi/chats/{uuid}',           [USER\ChatController::class,'chats']);
   Route::get('/cloudapi/chats2/{uuid}',           [USER\ChatController::class,'chats2']);
   Route::get('/get-chats/{uuid}',             [USER\ChatController::class,'chatHistory']);
   Route::get('/get-message/{number}/{uuid}',  [USER\ChatController::class,'chatMessage']);
   Route::post('/send-message/{uuid}',          [USER\ChatController::class,'sendMessage'])->name('chat.send-message');
   Route::post('/update-label/{id}',             [USER\ChatController::class,'updateTagLabel']);
   Route::post('/update-pinned/{id}',            [USER\ChatController::class,'updatePinned']);
   Route::post('/update-mute/{id}',             [USER\ChatController::class,'updateMute']);
   Route::post('/clear-messages/{id}',          [USER\ChatController::class, 'clearMessages']);

   

   Route::get('/cloudapi/groups/{uuid}',          [USER\ChatController::class,'groups']);
   Route::post('/get-groups/{uuid}',            [USER\ChatController::class,'groupHistory']);
   Route::post('/send-group-message/{uuid}',    [USER\ChatController::class,'sendGroupMessage'])->name('group.send-message');

   //app routes
   Route::resource('apps',                      USER\AppsController::class);
   Route::get('/app/integration/{uuid}',        [USER\AppsController::class,'integration'])->name('app.integration');
   Route::get('/app/messages-logs/{uuid}',      [USER\AppsController::class,'logs'])->name('app.logs');

   //template routes
   Route::resource('template',                  USER\TemplateController::class);
   Route::post('/template/store/{type}',        [USER\TemplateController::class,'store'])->name('template.store-now');
   Route::post('/get-template',                  [USER\TemplateController::class,'getTemplate']);

   //single send or custom text routes
   Route::get('/sent-text-message',                [USER\CustomTextController::class,'index']);
   Route::post('/sent-whatsapp-custom-text/{type}',[USER\CustomTextController::class,'sentCustomText'])->name('sent.customtext');
   Route::post('/template-details',             [USER\CustomTextController::class,'templateDetails']);

   //bulk sender routes
   Route::post('/bulk-messages',                          [USER\BulkController::class,'store'])->name('bulk-message.store');
   Route::resource('/bulk-message',                       USER\BulkController::class);
   Route::get('bulk-message/template-with-message/create',[USER\BulkController::class,'templateWithMessage']);
   Route::get('/sent-bulk-with-template', [USER\BulkController::class, 'sendBulkToContacts'])->name('template.bulk');
   Route::get('/check-campaign', [USER\BulkController::class, 'checkCampaign'])->name('template.bulk');

   Route::post('/sent-message-with-template',             [USER\BulkController::class,'sendMessageToContact']);
   //schedule message routes
   Route::resource('schedule-message',                    USER\ScheduleController::class);
   Route::get('/campaign/report/{id}',      [USER\ScheduleController::class, 'downloadReport'])->name('download.report');
   //schedule message routes
   Route::resource('contact',                             USER\ContactController::class);
   Route::post('contact',                                 [USER\ContactController::class,'sendtemplateBulk'])->name('contact.send-template-bulk');
   Route::post('contact/store',                           [USER\ContactController::class,'store'])->name('contact.store');
   Route::post('contact-import',                          [USER\ContactController::class,'import'])->name('contact.import');
   //chatbot route
   Route::resource('chatbot',                             USER\ChatbotController::class);
   //log report route
   Route::resource('logs',                                USER\LogController::class);
   //profile settings
   Route::get('profile',                                 [USER\ProfileController::class,'settings']);
   Route::put('profile/update/{type}',                   [USER\ProfileController::class,'update'])->name('profile.update');
   Route::get('auth-key',                                [USER\ProfileController::class,'authKey']);
   //help and support routes
   Route::resource('support',                            USER\SupportController::class);
   //subscription / plan route
   Route::resource('subscription',                       USER\SubscriptionController::class);
   Route::post('make-subscribe/{gateway_id}/{plan_id}',  [USER\SubscriptionController::class,'subscribe'])->name('make-payment');
   Route::get('/subscription/plan/{status}',             [USER\SubscriptionController::class,'status']);
   Route::get('/subscriptions/log',                      [USER\SubscriptionController::class,'log']);
   Route::get('/subscription-history',                   [USER\SubscriptionController::class,'log']);
   Route::resource('notifications',                      USER\NotificationController::class);
   Route::resource('group',                              USER\GroupController::class);
   Route::post('/embedded-signup',              [USER\CloudApiController::class,'embeddedSignup']);
   Route::post('/code-exchange',                [USER\CloudApiController::class, 'codeExchange']);
   
   Route::resource('role', 	     			             USER\RoleController::class);


});






?>