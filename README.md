# Recap steps Laaravel Auth

- Istall laravel `composer requrie laravel/laravel^9.2 project-name`
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

## Relationships OneToMany

- create the categories table `php artisan make:migration create_categories_table`

```php
// You need to fill the migration file
```

- create migration to add foreign key to posts table `php artian  make:migration add_foreign_category_id_to_posts_table`

```php
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add the column first
            $table->unsignedBigInteger('category_id')->nullable()->after('id');

            // Add the foreign key
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }
```

down method migration

```php

/**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // drop the constrains
            $table->dropForeign('posts_category_id_foreign');
            // drop the column
            $table->dropColumn('category_id');
        });
    }
```

Migrate the db

```php

php artisan migrate
```

## Add models relationship

Add has many relationship between Post -> Category

Definition: A post belongs to a category.

```php
// Post.php
/**
     * Get the category that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


```

😱 Remember:
You need to import `use Illuminate\Database\Eloquent\Relations\BelongsTo;`

😱 Remember:
to the new field add category_id to fillable properties in Post.php.

The inverse of the relation

Definition: A category has many posts.

```php
// Category.php
public function posts(): HasMany
  {
      return $this->hasMany(Post::class);
  }

```

## Edit the CRUD to add categories to views create

Pass all categories in the create method and compact to the view

```php
    public function create()
    {


        $categories = Category::orderByDesc('id')->get();

        return view('admin.posts.create', compact('categories'));
    }

```

Edit the [create] view to show a select

```php

    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
            <option value="">Select a category</option>
            @foreach ($categories as $category)
            <option value="{{$category->id}}" {{ $category->id  == old('category_id', '') ? 'selected' : '' }}>{{$category->name}}</option>
            @endforeach
        </select>
    </div>
```

## Validate the category selected

You need to add a validation rule to validate the category_id.
Edit the StorePostRequest.php

```php
// StorePostRequest.php
public function rules()
    {
        return [
            'title' => ['required', 'unique:posts', 'max:150'],
            'cover_image' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'category_id' => ['exists:categories,id'] // 👈 Add validation ['exists:table,column']
        ];
    }

```

## Edit the CRUD to add categories to view edit

Pass all categories in the edit method and compact to the view

```php
    public function edit(Post $post)
    {
        $categories = Category::orderByDesc('id')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

```

Edit the [edit] view to show a select

```php

   <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select @error('category_id') is-invalid @enderror" name=" category_id" id="category_id">
            <option value="">Select a category</option>
            @foreach ($categories as $category)                                       //👇 We need to take the current category associated to the post.
            <option value="{{$category->id}}" {{ $category->id  == old('category_id', $post->category->id) ? 'selected' : '' }}>{{$category->name}}</option>
            @endforeach
        </select>
    </div>
```

## Validate the category selected

You need to add a validation rule to validate the category_id.
Edit the UpdatePostRequest.php

```php
// UpdatePostRequest.php
public function rules()
    {
        return [
            'title' => ['required', 'unique:posts', 'max:150'],
            'cover_image' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'category_id' => ['exists:categories,id'] // 👈 Add validation ['exists:table,column']
        ];
    }

```

Show the category name in the show view

```php
<div class="meta">
    <span class="badge bg-primary">{{$post->category?->name}}</span>
</div>
```

## Many To Many

- create db table for the given Technology (-a)
- create db table for pivot table create_model1_model2_table (in alphabetic order)
- seeder (bonus)
- add models relationships (belogsToMany) inside both Models (see the live coding)

example from Post->Tags

```php
// Post.php
  /**
     * The tags that belong to the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
```

Nota: add the inverse relationship Tag->Post (BelongsToMany)

- add to ProjectController in create/edit all Technology data and pass to the view (Model::all())
- edit the two view (create/edit) adding the required checkboxes (use the snipped)
- validate your Technology fields inside Store and Update FormRequests (<https://laravel.com/docs/9.x/validation#rule-exists>)
- attach and sync the relationships inside store and update methods

PostControler@store

```php

// Attach the checked tags
if ($request->has('tags')) {
    $new_post->tags()->attach($request->tags);
}
// redirect to_route
```

PostControler@update

```php
// sync the new updated tags
if ($request->has('tags')) {
    $post->tags()->sync($request->tags);
}
// redirect to_route

```

## Snippets per vsCode

nel file html.json (File>Preference>ConfigureUserSnippets) select html.json

```json
{

  // Other snippets here

 "Laravel Blade Many2Many checkbox view create": {
  "prefix": "@?check-create",
  "body": [
   "<div class='form-group'>",
   "<p>Seleziona i tag:</p>",
   "@foreach ($$tags as $$tag)",
   "<div class=\"form-check @error('tags') is-invalid @enderror\">",
   "<label class='form-check-label'>",
   "<input name='tags[]' type='checkbox' value='{{ $$tag->id}}' class='form-check-input' {{ in_array($$tag->id, old('tags', [])) ? 'checked' : '' }}>",
   "{{ $$tag->name }}",
   "</label>",
   "</div>",
   "@endforeach",
   "@error('tags')",
   "<div class='invalid-feedback'>{{ $$message}}</div>",
   "@enderror",
   "</div>"
  ]
 },
 "Laravel Blade Many2Many checkbox view edit": {
  "prefix": "@?check-edit",
  /*   "description": "1 se ci sono degli errori di validazione signifca che bisogna recuperare i tag selezionati tramite la funzione old(),la quale restituisce un array plain contenente solo gli id - 2 se non sono presenti errori di validazione significa che la pagina è appena stata aperta per la prima volta, perciò bisogna recuperare i tag dalla relazione con il post, che è una collection di oggetti di tipo Tag", */
  "body": [
   "<div class='form-group'>",
   "<p>Seleziona i tag:</p>",
   "@foreach ($$tags as $$tag)",
   "<div class=\"form-check @error('tags') is-invalid @enderror\">",
   "<label class='form-check-label'>",
   "@if($$errors->any())",
   "$BLOCK_COMMENT_START 1 (if) $BLOCK_COMMENT_END",
   "<input name=\"tags[]\" type=\"checkbox\" value=\"{{ $$tag->id}}\" class=\"form-check-input\" {{ in_array($$tag->id, old('tags', [])) ? 'checked' : '' }}>",
   "@else",
   "$BLOCK_COMMENT_START 2 (else) $BLOCK_COMMENT_END",
   "<input name='tags[]' type='checkbox' value='{{ $$tag->id }}' class='form-check-input' {{ $$post->tags->contains($$tag) ? 'checked' : '' }}>",
      "@endif",
   "{{ $$tag->name }}",
   "</label>",
   "</div>",
   "@endforeach",
   "@error('tags')",
   "<div class='invalid-feedback'>{{ $$message}}</div>",
   "@enderror",
   "</div>"
  ]
 }
}

```
