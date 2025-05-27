<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Models\Demande;
use App\Models\Notification;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === UserRole::User) {
                $notifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($notif) {
                        return [
                            'id' => $notif->id,
                            'titre' => $notif->titre,
                            'temps' => $notif->created_at->diffForHumans(),
                            'demande_id' => $notif->demande_id,
                        ];
                    });

                $view->with('demandes', $notifications);
            }
        });
    }
}