<?php

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserListController;


Route::controller(AuthController::class)->prefix("auth")->middleware('api')->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
    Route::post('refresh', 'refresh')->name('auth.refresh');


    Route::middleware('jwt.auth.token')->group(function () {
        Route::post("logout", "logout")->name("auth.logout");
        Route::get('user-profile', 'userProfile')->name('auth.user.profile');
        Route::post("send-registration-invite", "sendRegistrationInvite")->name("auth.sendRegistrationInvite");
        
    });

   
    // kriju shume api
});

Route::controller(UserListController::class)->prefix("lists")->middleware('api')->group(function () {
    Route::middleware('jwt.auth.token')->group(function () {
        Route::get('/', 'index')->name('lists.index');
        Route::post("store", "store")->name("lists.store");
        Route::patch("update-list-name/{id}", "updateListName")->name("lists.updateListName");
        Route::delete("/{id}/delete", "destroy")->name("lists.destroy");
        
        Route::get('/{id}/users', 'getUsersInList')->name('lists.getUsersInList');
        Route::get('/{id}/available-users', 'getAllAvailableUsers')->name('lists.getAllAvailableUsers');
        Route::post("{id}/add-users", "addUsersToList")->name("lists.addUsersToList");
        Route::delete("/{id}/remove-user", "removeUserFromList")->name("lists.removeUserFromList");
        
        Route::get('/{id}/pick-random-winner', 'pickRandomWinner')->name('lists.pickRandomWinner');
    });
});

// Route::get('test-api-endpoint', function() {
//     return response()->json(['message' => 'API endpoint is working']);
// });

Route::post('test-mail-sent', function(Request $request) {
    try {
        $mailData = [
            'title' => 'Email Title',
            'message' => 'This is a test e-mail directed to only students of Lutfi Musiqi High School.',
            'session_title' => $request->session_title
        ];

        $cc_users = [];
        Mail::to('nas2dev@gmail.com')->send(new TestMail($mailData));

        return response()->json('success');
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'type' => class_basename($e)
            ]
        ], 500);
    }
});

Route::get('zen-quote', function() {

    try {
        $reponse = Http::get("https://zenquotes.io/api/random");

        if($reponse->successful()) {
            $quote = $reponse->json()[0];

            return response()->json([
                'success' => true,
                'quote' => [
                    'text' => $quote['q'],
                    'author' => $quote['a'],
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch quote from external API'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch quote from external API',
            'error' => [
                'message' => 'Failed to fetch quote',
                'details' => $e->getMessage()
            ]
        ]);
    }

});