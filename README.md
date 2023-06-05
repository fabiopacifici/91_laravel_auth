# Recap steps Laaravel Auth

- Istall laravel `compsoer requrie laravel/laravel^9.2 project-name`
- Install Breeze starter kit `composer require laravel/breeze --dev`
- Run Breeze command `php artisan breeze:install`
- Install Laravel BS preset `composer require pacificdev/laravel_9_preset`
- Run preset command `php artisan preset:ui bootstrap --auth` and `npm i`
and `npm run dev`
- Refactor dashboard route closure to controller `php artisan meke:controller Admin/DashboardController`
- Add index method in the DashboardController

```php
    public function index()
    {
        return view('admin.dashboard');
    }
```

Create the view/admin folder
Move dashboard.blade.php into admin/

- Edit the dashboard route
  
```php
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
```

Rename dashboard uri in admin and update the RouteServiceProvider

```php
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard'); 

```

Update the RouteServicePrivider file in (Http/Providers folder)

from
`public const HOME = '/dashboard';`

to

`public const HOME = '/admin';`

Make a route group for the Dashboard and other admin routes

```php
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // responds to url /admin
    // Occhio Importa il controller 🧠
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // admin.dashboard
   
});
```

Crete the admin.blade.php layout in layouts/

```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css' integrity='sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==' crossorigin='anonymous' referrerpolicy='no-referrer' />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">BoolPress</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="#">Sign out</a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid vh-100">
            <div class="row h-100">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{Route::currentRouteName() == 'admin.dashboard' ? 'bg-dark' : ''}}" aria-current="page" href="{{route('admin.dashboard')}}">
                                    <i class="fa-solid fa-gauge"></i>
                                    {{__('Dashboard')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{Route::currentRouteName() == 'admin.posts.index' ? 'bg-dark' : ''}}" href="{{route('admin.posts.index')}}">
                                    <i class="fa-solid fa-thumbtack"></i>
                                    {{__('Posts')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#">
                                    <i class="fa-solid fa-bookmark"></i>
                                    Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#">
                                    <i class="fa-solid fa-tags"></i>
                                    Tags
                                </a>
                            </li>

                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    @yield('content')
                </main>
            </div>
        </div>

    </div>
</body>

</html>

```

The incomplete (cannot log out the user yet) layout above will extended by admin pages

Create Model+Controller+Seeder+Migration+etc

```bash
php artisan make:model Post -a
```

- fill migration

```php
 Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('title', '150')->unique();
      $table->string('slug');
      $table->string('cover_image')->nullable();
      $table->text('content')->nullable();
      $table->timestamps();
  });

```

- fill seeder

```php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str; // 👈  Importami


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 10; $i++) {

            $post = new Post();
            $post->title = $faker->sentence(3);
            $post->slug = Str::slug($post->title, '-'); // 👈  Use me to generate a slug
            $post->content = $faker->paragraphs(asText: true); 
            $post->cover_image = $faker->imageUrl(category: 'Posts', format: 'jpg');
            $post->save();
        }
    }
}
```

- setup fillable properties in Post.php

Add the PostSeerder to the main db seeder file

```php
 public function run()
    {

        $this->call([
            PostSeeder::class
        ]);
    }

```

This allows to call the following comman to migrate and seed
`php artisan migrate --seed`
Or

`php artisan db:seed --class=PostSeeder`

- Gererate slug snippet inside the Model

```php
// Post.php

    protected $fillable = ['title', 'content', 'cover_image', 'slug'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
```

Add Routes for Posts inside the admin group

```php
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // responds to url /admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // admin.dashboard
    Route::resource('posts', PostController::class); // 👈 Add the resource route inside the group
});

```

Standard CRUD OPS inside the PostController now 👇
