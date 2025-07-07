<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function create(User $user, string $title, string $message, string $type = 'system', array $data = [])
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Create transaction status notification
     */
    public static function transactionStatus($transaction, string $status)
    {
        $user = $transaction->user;
        $game = $transaction->topupPackage->game;
        $package = $transaction->topupPackage;

        $title = match($status) {
            'completed' => 'Top-up Berhasil!',
            'failed' => 'Top-up Gagal',
            'processing' => 'Top-up Sedang Diproses',
            default => 'Update Status Top-up',
        };

        $message = match($status) {
            'completed' => "Top-up {$game->name} ({$package->name}) telah berhasil diproses. {$package->amount} {$game->currency_name} telah ditambahkan ke akun Anda.",
            'failed' => "Top-up {$game->name} ({$package->name}) gagal diproses. Silakan hubungi customer service untuk bantuan.",
            'processing' => "Top-up {$game->name} ({$package->name}) sedang diproses. Mohon tunggu sebentar.",
            default => "Status top-up {$game->name} ({$package->name}) telah diperbarui.",
        };

        $type = match($status) {
            'completed' => 'transaction_completed',
            'failed' => 'transaction_failed',
            'processing' => 'transaction_processing',
            default => 'system',
        };

        return self::create($user, $title, $message, $type, [
            'transaction_id' => $transaction->id,
            'transaction_code' => $transaction->transaction_code,
            'game_name' => $game->name,
            'package_name' => $package->name,
        ]);
    }

    /**
     * Create promo notification
     */
    public static function promo(User $user, string $title, string $message, array $data = [])
    {
        return self::create($user, $title, $message, 'promo', $data);
    }

    /**
     * Create system notification
     */
    public static function system(User $user, string $title, string $message, array $data = [])
    {
        return self::create($user, $title, $message, 'system', $data);
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(Notification $notification)
    {
        return $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(User $user)
    {
        return $user->unreadNotifications()->update(['is_read' => true]);
    }
} 