<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\TestPostRequest;
use App\Http\Middleware\AttemptBearerToken;
use App\Http\Middleware\CheckTokenAndAddToHeaderMiddleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Guard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Inertia::render("Home");
})->name('home');

Route::middleware(CheckTokenAndAddToHeaderMiddleware::class)->get('/test', function (Request $request) {
    dd($request->user('sanctum'), $request->bearerToken());
    dd(auth('sanctum')->check($request->token));

    return redirect(route('auth'))->header('Authorization', 'Bearer ' . $request->token);
})->name('test');

Route::get('/redirect', function (Request $request) {
    auth()->loginUsingId(1);
    $request->session()->put('state', $state = \Str::random(40));

    $query = http_build_query([
        'client_id' => '9a362367-7c28-45f5-88f5-eca2dd604fcc',
        'redirect_uri' => 'http://localhost:8000/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => 'none', // "none", "consent", or "login"
    ]);

    return redirect('/oauth/authorize?' . $query);
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class,
        'Invalid state value.'
    );

    $response = Http::asForm()->post('http://localhost:8000/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => '9a362367-7c28-45f5-88f5-eca2dd604fcc',
        'client_secret' => 'RGufUHaEuN0uvYWJd7FiDYIuDBX1JCYFSHzEF1HA',
        'redirect_uri' => 'http://localhost:8000/callback',
        'code' => $request->code,
    ]);
    dd($response, $response->status());
    return $response->json();
});
