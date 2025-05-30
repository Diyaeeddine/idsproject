<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
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
                $userId = Auth::id();

                $notifications = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->whereIn('demande_id', function ($query) use ($userId) {
                        $query->select('demande_id')
                              ->from('demande_user')
                              ->where('user_id', $userId)
                              ->where('IsYourTurn', true);
                    })
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
