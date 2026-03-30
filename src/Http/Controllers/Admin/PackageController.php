<?php

namespace Zain\BillForge\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zain\BillForge\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Route as RouteFacade;

class PackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::withCount('subscriptions', 'routes')->get();
        return view('subscriptions::admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $appRoutes = $this->getAppRoutes();
        return view('subscriptions::admin.packages.create', compact('appRoutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'currency'         => 'required|string|size:3',
            'billing_cycle'    => 'required|string|in:monthly,yearly,lifetime',
            'stripe_price_id'  => 'nullable|string',
            'paypal_plan_id'   => 'nullable|string',
            'authorize_plan_id'=> 'nullable|string',
            'is_active'        => 'boolean',
            'features'         => 'nullable|array',
            'features.*.name'  => 'required_with:features|string|max:255',
            'features.*.route' => 'nullable|string',
        ]);

        $package = SubscriptionPackage::create(\Illuminate\Support\Arr::except($validated, ['features']));

        if (!empty($validated['features'])) {
            $routesData = [];
            foreach ($validated['features'] as $feature) {
                if (!empty($feature['name'])) {
                    $routeName = $feature['route'] ?? null;
                    $routeUri  = $routeName ? $this->getRouteUri($routeName) : null;
                    $routesData[] = [
                        'feature_name' => $feature['name'],
                        'route_name'   => $routeName,
                        'route_uri'    => $routeUri,
                    ];
                }
            }
            $package->routes()->createMany($routesData);
        }

        return redirect()->route('subscriptions.admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(SubscriptionPackage $package)
    {
        $package->load('routes');
        $appRoutes = $this->getAppRoutes();
        return view('subscriptions::admin.packages.edit', compact('package', 'appRoutes'));
    }

    public function update(Request $request, SubscriptionPackage $package)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'currency'         => 'required|string|size:3',
            'billing_cycle'    => 'required|string|in:monthly,yearly,lifetime',
            'stripe_price_id'  => 'nullable|string',
            'paypal_plan_id'   => 'nullable|string',
            'authorize_plan_id'=> 'nullable|string',
            'is_active'        => 'boolean',
            'features'         => 'nullable|array',
            'features.*.name'  => 'required_with:features|string|max:255',
            'features.*.route' => 'nullable|string',
        ]);

        $package->update(\Illuminate\Support\Arr::except($validated, ['features']));

        $package->routes()->delete();
        if (!empty($validated['features'])) {
            $routesData = [];
            foreach ($validated['features'] as $feature) {
                if (!empty($feature['name'])) {
                    $routeName = $feature['route'] ?? null;
                    $routeUri  = $routeName ? $this->getRouteUri($routeName) : null;
                    $routesData[] = [
                        'feature_name' => $feature['name'],
                        'route_name'   => $routeName,
                        'route_uri'    => $routeUri,
                    ];
                }
            }
            $package->routes()->createMany($routesData);
        }

        return redirect()->route('subscriptions.admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(SubscriptionPackage $package)
    {
        $package->delete();
        return redirect()->route('subscriptions.admin.packages.index')->with('success', 'Package deleted successfully.');
    }

    private function getAppRoutes(): array
    {
        $routes = [];
        foreach (RouteFacade::getRoutes()->getRoutesByName() as $name => $route) {
            if (str_starts_with($name, 'ignition') || str_starts_with($name, 'subscriptions.admin')) {
                continue;
            }
            $routes[] = [
                'name' => $name,
                'uri'  => '/' . ltrim($route->uri(), '/'),
            ];
        }
        return $routes;
    }

    private function getRouteUri(string $routeName): ?string
    {
        try {
            $route = RouteFacade::getRoutes()->getByName($routeName);
            return $route ? '/' . ltrim($route->uri(), '/') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
