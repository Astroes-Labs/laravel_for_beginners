<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
//use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;

use Laravel\Socialite\Facades\Socialite;


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
    return view('welcome');
    //##read all users 
    //$users = DB::select("select * from users");
    //##read particular user
    //$users = DB::select("select * from users where email=?",['astroeslabs@gmail.com']);
    //##create new user
    //$users = DB::insert("insert into users (name, email, password) values (?, ?, ?)",['Astroes Labs','astroeslabs@mail.com','astroeslabs']);
    //##update new user
    //$users = DB::update("update users set email=? where id =? ",['astro@gmail.com', 2]);
    //##delete new user
    //$users = DB::delete("delete from users where id =? ",[2]);
    //## QUERY BUILDERS
    //## read particular user
    //$users = DB::table('users')->where('id', 1)->get();
    //OR

    //## read
    //$users = DB::table('users')->find(1);
    //$users = User::find(1);
    //## read first user
    //$users = DB::table('users')->first();
    //## read all users
    //$users = DB::table('users')->get();
    //## read particular value
    //$email = DB::table('users')->where('id', 1)->value('email');

    //## insert
    /*  $users = DB::table('users')->insert([
         'name' => 'Astro',
         'email' => 'astro@gmail.com',
         'password' => 'password'
     ]);

      */
    //## update
    //$users = DB::table('users')->where('id', 3)->update(['email' => 'astro3@gmail.com']);

    //## delete
    //$users = DB::table('users')->where('id', 3)->delete();


    //##eloquent orm read

    /*  $users = User::get();
     //OR 
    $users = User::all();

     $users = User::find(17);

     */
    //##eloquent orm create
/* 
    $users = User::create([
        'name' => 'Astrobbe',
        'email' => 'newwestds@gmail.com',
        'password' => 'password'
    ]);
 */

    //##eloquent orm update
    /*  $users = User::where('id', 6)->first();
     $users->update(['email' => 'Goastro@gmail.com']);
     //##OR
     $users = User::find(6);
     $users->update(['email' => 'Goastro@gmail.com']);
      */

    //##eloquent orm delete
    /*  $users = User::where('id', 6)->first();
     $users->delete(); 
     //##OR
     $users = User::find(6);
     $users->delete();
     */

    //dd($users->name);//dump and die
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
Route::post('/profile/avatar/ai', [AvatarController::class, 'generate'])->name('profile.avatar.ai');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user =User::firstOrCreate(['email' => $user->email], [
        'name' => $user->name,
        'password' => 'password',

    ]);
    //dd($user->email);
    Auth::login($user);
    return redirect('/dashboard');
    // $user->token
});
require __DIR__ . '/auth.php';
