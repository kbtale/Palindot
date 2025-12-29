# Palindot

**Palindot** is a URL shortener SPA built with Laravel and Vue 3. It creates palindromic short codes (symmetrical strings) for shortened URLs.

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
