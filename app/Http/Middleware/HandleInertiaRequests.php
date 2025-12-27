<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Setting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = session('locale', config('app.locale'));
        
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'locale' => $locale,
            'translations' => function () use ($locale) {
                return trans('app', [], $locale);
            },
            'settings' => [
                'contact_phone' => Setting::get('contact_phone', '01 23 45 67 89'),
                'contact_email' => Setting::get('contact_email', 'contact@centralautob.fr'),
                'contact_address' => Setting::get('contact_address', '123 Rue de l\'Exemple, 75000 Paris'),
                'opening_hours_week' => Setting::get('opening_hours_week', '09h00 - 18h00'),
                'opening_hours_weekend' => Setting::get('opening_hours_weekend', '09h00 - 12h00'),
            ],
        ];
    }
}
