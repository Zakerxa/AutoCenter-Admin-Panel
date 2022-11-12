<?php

use App\Http\Controllers\ClientController;
use App\Models\client;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule as ValidationRule;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
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
Route::post('/login',function(Request $request){
    $admin = $request->validate([
        'email' => ['required', 'email', ValidationRule::exists('users', 'email'), 'max:255'],
        'password' => ['required', 'min:3', 'max:50']
    ]);
    // Auth User
    if (auth()->attempt($admin)) {
        // Check Email
        $user = User::where('email', $admin['email'])->first();
        $token = $user->createToken('auth')->plainTextToken;
        return response()->json([
            'response' => 'success',
            'token' => $token
        ]);
    } else {
        return response(['response' => 'The provided credentials do not match our records.']);
    }
});

// Protected Route
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/clients',[ClientController::class,'index']);
    Route::post('/clients/form',[ClientController::class,'store']);
    Route::post('/clients/read/{id}',[ClientController::class,'read']);
    Route::post('/delete/{id}',[ClientController::class,'destory']);
    Route::get('/logout',[ClientController::class,'logout']);
    // Check Mail
    Route::post('/clients/email',[ClientController::class,'sendmail']);
    // Admin Info
    Route::get('/user', function() {return Auth::user()?? 'Logout User';});
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
