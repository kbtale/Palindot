<h1 align="center">Palindot</h1>

<p align="center">
  A URL shortener SPA built with Laravel and Vue 3 that creates palindromic short codes.
  <br>
  <br>
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vue.js&logoColor=white" alt="Vue.js" />
  <img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?logo=tailwind-css&logoColor=white" alt="Tailwind" />
  <img src="https://img.shields.io/badge/Docs-Swagger-85EA2D?logo=swagger&logoColor=black" alt="Swagger" />
  <img src="https://img.shields.io/badge/Test-Pest-purple" alt="Pest" />
</p>
<hr>

## Implementation Details

* **Slug Generation:** The core logic (`app/Models/Url.php`) hashes the target URL using `sha256`, takes a substring, and mirrors it (e.g., `abc` → `abc` + `cba` → `abccba`) to enforce a palindrome.
* **Backend:** Laravel 10 acting as a REST API.
    * Auth: Laravel Sanctum.
    * Docs: `zircote/swagger-php` annotations in controllers.
* **Frontend:** Vue 3 + Tailwind CSS.
    * State: Standard Vue reactivity.
    * Routing: Vue Router.
* **Testing:** Pest PHP.

## Stack

* **PHP:** 8.1+
* **Framework:** Laravel 10
* **Frontend:** Vue.js 3, Tailwind CSS
* **Bundler:** Vite
* **Database:** MySQL

## Local Development

**1. Setup**

```bash
git clone [https://github.com/kbtale/Palindot.git](https://github.com/kbtale/Palindot.git)
cd Palindot

composer install
npm install

cp .env.example .env
php artisan key:generate
