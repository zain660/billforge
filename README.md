# Laravel Subscriptions Package

A powerful, full-featured SaaS subscription management package for Laravel. It provides dynamic route protection, multiple gateway support, and a beautiful Admin Dashboard.

## Features
- **Admin Dashboard**: A modern TailwindCSS control panel for your subscriptions.
- **Multiple Gateways**: Built-in support for Stripe, PayPal, and Authorize.net. Add credentials directly from the UI.
- **Subscription Tiers**: Create unlimited subscription packages, link them to gateway plans (e.g., Stripe Price IDs).
- **Dynamic Route Blocking**: Secure your app's routes. Assign access permissions via the admin dashboard, and use the included middleware to protect your SaaS features dynamically.

## Installation

1. Require the package locally (if developing) or via composer:
```bash
composer require zain/laravel-subscriptions
```

2. Publish the package assets and configuration:
```bash
php artisan vendor:publish --provider="Zain\LaravelSubscriptions\SubscriptionServiceProvider"
```

3. Run migrations:
```bash
php artisan migrate
```

## Setup & Usage

### 1. Protect Routes
Add the middleware `subscription.access` to any routes you'd like to protect dynamically from the admin panel:
```php
Route::get('/saas/premium-feature', [PremiumController::class, 'index'])
    ->middleware(['auth', 'subscription.access'])
    ->name('saas.feature');
```
*Note: Users without an active subscription mapped to this route will be blocked.*

### 2. Update User Model
Add the `HasSubscriptions` trait to your host application's `User` model (`App\Models\User`):

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zain\LaravelSubscriptions\Traits\HasSubscriptions;

class User extends Authenticatable
{
    use HasSubscriptions;
    
    // ...
}
```

### 3. Access the Admin Dashboard
Navigate to `/admin/subscriptions` in your application to access the modern control panel. From there, you can configure your Payment Gateways, set up Packages, and map them to standard application Routes.
