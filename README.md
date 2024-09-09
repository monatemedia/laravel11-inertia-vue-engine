<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About

Built with 
- PHP 8.2
  - Laravel 11 with Breeze Starter Kit
    - Inertia.js
      - Vue.js v3
        - Tailwind CSS
        - Hero Icons
  - Composer
- Node.js
  - NPM

## Road Map

[X] Create New Project
[X] Install Breeze
[X] Run Project in Terminal
[X] Install Dark Mode
    [X] Install Dark Mode switcher button
        [X] Pages
        [X] Authenticated Layout
[ ] Extend User Model
    [ ] Accept Country on Registration
    [ ] Accept Phone Number on Registration 

## Create New Project

```sh
composer create-project laravel/laravel laravel11-inertia-vue-engine
```

Change into Directory

```sh
cd laravel11-vue-inertia-dynamo
```

Open with VS Code
```sh
code .
```

## Download and Install Breeze

Download Laravel Breeze

```sh
composer require laravel/breeze --dev
```

Install Laravel Breeze

```sh
php artisan breeze:install
```

Selections:

1. Which Breeze stack would you like to install?
    - vue

2. Would you like any optional features?
    - dark, ssr

3. Which testing framework do you prefer?
    - 0

## Run Project In Terminal

Run Artisan Serve in terminal

```sh
php artisan serve
```

Run NPM Run in a second terminal

```sh
npm run dev
```

Run PHP Artisan Tinker in third terminal

```sh
php artisan tinker
```

Run open terminal in fourth terminal

## Build Dark Mode Component

1. Edit /tailwind.config.js

```sh
export default {
    darkMode: 'class', //Add this line 
    ...
};
``` 

2. Install Hero Icons

```sh
npm i @heroicons/vue --include=dev
```

[Hero Icons](https://heroicons.com/) can be imported and used as components

3. Create /resources/js/Components/DarkModeToggle.vue component with this code:

```sh
<script setup>
import { ref, onMounted } from 'vue';
import { SunIcon } from '@heroicons/vue/24/outline'; // Import icons
import { MoonIcon } from '@heroicons/vue/24/solid'; // Import icons

// Reactive variable to track if dark mode is enabled
const isDarkMode = ref(false);

// Function to toggle dark mode
const toggleDarkMode = () => {
  const html = document.documentElement;

  if (isDarkMode.value) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }

  // Toggle the dark mode state
  isDarkMode.value = !isDarkMode.value;
};

// Check for stored theme preference on page load
onMounted(() => {
  if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
    isDarkMode.value = true;
  } else {
    document.documentElement.classList.remove('dark');
    isDarkMode.value = false;
  }
});
</script>

<template>
  <button
    @click="toggleDarkMode"
    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5"
  >
    <MoonIcon v-if="!isDarkMode" class="w-5 h-5" />
    <SunIcon v-if="isDarkMode" class="w-5 h-5" />
  </button>
</template>
```

4. Edit /resources/js/Pages/Welcome.vue

- Import DarkModeToggle component, Dark Mode Toggle Script and use `<DarkModeToggle />`

```sh
<script setup>

...
import DarkModeToggle from '@/Components/DarkModeToggle.vue'; // Import DarkModeToggle component
...

...
<template v-else>
    <!-- Dark Mode Toggle -->
    <DarkModeToggle />
    <Link
        :href="route('login')"
        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
    >
...
```

5. Edit /resources/js/Layouts/AuthenticatedLayout.vue

- Import DarkModeToggle component, Dark Mode Toggle Script and place `<DarkModeToggle />` component anywhere in your template where you want to use it.

```sh
<script setup>

...
import DarkModeToggle from '@/Components/DarkModeToggle.vue'; // Import DarkModeToggle component
...

...
<div class="hidden sm:flex sm:items-center sm:ms-6">
    <!-- Dark Mode Toggle -->
    <DarkModeToggle />
    <!-- Settings Dropdown -->
    <div class="ms-3 relative">
        <Dropdown align="right" width="48">
            <template #trigger>
...

...
<!-- Hamburger -->
<div class="-me-2 flex items-center sm:hidden">
    <!-- Dark Mode Toggle -->
    <DarkModeToggle />
    <button>
...
```

## Extend User Model To Accept Country And Phone Number

1. Create migration to Add Country and Phone Number Fields

```sh
php artisan make:migration add_country_and_phone_number_to_users_table --table=users
```

Then in the migration file, add these fields:

```sh
...
Schema::table('users', function (Blueprint $table) {
            // Add country and phone_number columns
            $table->string('country')->after('email')->nullable();
            $table->string('phone_number')->after('country')->nullable();
        });
...

...
Schema::table('users', function (Blueprint $table) {
            // Drop country and phone_number columns
            $table->dropColumn('country');
            $table->dropColumn('phone_number');
        });
...
```

Once you've added the changes, run:

```sh
php artisan migrate
```

2. Updating the `User` Model to handle additional fields

Edit /app/Models/User.php

```sh
protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'phone_number',
    ];
```

3. Updating the Registration Form

Edit /resources/js/Pages/Auth/Register.vue 

```sh
<script setup>
import { ref, onMounted } from 'vue';
...

...
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    country: '',
    phone_number: '',
});
...

...
// initialise country list
const countries = ref([]);

// Get countries via API
onMounted(() => {
    axios.get('https://restcountries.com/v3.1/all')
        .then(response => {
            countries.value = response.data.map(country => ({
                name: country.name.common,
                code: country.cca2,
            }));
        });
});
</script>
...
```

4. Edit app/Http/Controllers/Auth/RegisteredUserController.php

```sh
...
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'country' => 'required|string|max:255', // Country is required
    'phone_number' => 'required|string|min:10|max:15', // Phone number validation
]);

$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'country' => $request->country, // Store country
    'phone_number' => $request->phone_number, // Store phone number
]);
...
```

5. Make RegisteredUserRequest Form Request

```sh
php artisan make:request RegisteredUserRequest
```



## Make Models and Migrations

Make Package Model
php artisan make:model Package -m

Make Transaction Model
php artisan make:model Transaction -m

Make Feature Model
php artisan make:model Feature -m

Make UsedFeature Model
php artisan make:model UsedFeature -m

## Create User Observer

Make user observer
php artisan make:observer UserObserver

## Run Migrations with Seeders

Rerun Migrations and Seed Database
php artisan migrate:fresh --seed

## Artisan Tinker Database Queries

Enter Tinker
php artisan tinker

Check DB Connection
DB::connection()->getDatabaseName()
Should return = "C:\xampp\htdocs\laravel11-vue-inertia-dynamo\database\database.sqlite"

Get First User Data
User::first()

Get All User Data
User::all()

Get All Features
\App\Models\Feature::all()

## Make A Controller

Make Controller One
php artisan make:controller Feature1Controller

Make Controller Two
php artisan make:controller Feature2Controller

## Make A Resource

Resource controllers are important when using a front-end framework like vue or react. The Resource controller only passes the necessary data for the front-end to do it's work. Passing the data directly from the Controller will expose all data in the controller which would to vulnerabilities.

Make FeatureResource
php artisan make:resource FeatureResource

## Create Feature 1 Index Component

Create the component in /resources/js/Pages/Feature1/Index.vue

## Add Custom Feature Vue Components

Add Feature.vue component in /resources/js/Components/Feature.vue to use in all new Feature components you're gonna add.That's gonna be a generic component that display's the feature name, number of credits required for the specific feature, and this feature will also lock when the user has insufficient credits to use that feature.


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
