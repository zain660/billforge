<?php

namespace Zain\BillForge\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Zain\BillForge\Models\PackageRoute;

class CheckSubscriptionAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();
        $routeUri = $request->path();

        if (! $routeName) {
            return $next($request);
        }

        // Check if this route is protected
        $isProtected = PackageRoute::where('route_name', $routeName)
            ->orWhere('route_uri', $routeUri)
            ->exists();
        // dd($isProtected);
        if ($isProtected) {
            return $next($request);
        } else {
            return redirect()->route('subscriptions.pricing')
                ->with('error', 'Your current subscription package does not allow access to this feature.');
        }

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this feature.');
        }

        if (! method_exists($user, 'canAccessRoute')) {
            abort(403, 'User model does not use HasSubscriptions trait.');
        }

        if ($user->canAccessRoute($routeName)) {
            return $next($request);
        }

        // Redirect to upgrade page if exists
        if (\Route::has('subscriptions.upgrade')) {
            return redirect()->route('subscriptions.upgrade')
                ->with('error', 'Your current subscription package does not allow access to this feature.');
        }

        abort(403, 'Your current subscription package does not allow access to this feature.');
    }
}
