<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Demande;

class NotificationController extends Controller
{
    // Récupérer notifications non lues de l'utilisateur
public function getUnreadNotifications()
{
    $user = Auth::user();

    $notifications = Notification::where('user_id', $user->id)
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

    return response()->json([
        'success' => true,
        'notifications' => $notifications,
        'count' => $notifications->count(),
    ]);
}


    // Marquer une notification comme lue
public function markAsRead($id)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Utilisateur non authentifié'], 401);
    }

    $notification = Notification::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$notification) {
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    $notification->update([
        'is_read' => true,
        'read_at' => now(),
    ]);

    return response()->json(['success' => true]);
}





    // Générer notifications à partir des demandes non encore notifiées (à appeler à la connexion ou via cron)
    public function generateNotifications()
    {
        $user = Auth::user();

        // Par exemple : récupérer demandes non notifiées (pas encore en notifications)
        $demandes = Demande::whereNotIn('id', function($query) use ($user) {
            $query->select('demande_id')
                  ->from('notifications')
                  ->where('user_id', $user->id);
        })->get();

        foreach ($demandes as $demande) {
            Notification::create([
                'user_id' => $user->id,
                'demande_id' => $demande->id,
                'titre' => $demande->titre ?? 'Nouvelle demande',
            ]);
        }

        return response()->json(['success' => true]);
    }
}