<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--days=30 : Number of days to keep read notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old read notifications and duplicate unread notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Starting notification cleanup...");

        // 1. Delete old read notifications
        $deletedReadCount = Notification::where('read', true)
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Deleted {$deletedReadCount} old read notifications (older than {$days} days)");

        // 2. Remove duplicate unread notifications
        $duplicatesRemoved = $this->removeDuplicateNotifications();

        $this->info("Removed {$duplicatesRemoved} duplicate unread notifications");

        // 3. Mark resolved notifications as read
        $resolvedCount = $this->markResolvedNotificationsAsRead();

        $this->info("Marked {$resolvedCount} resolved notifications as read");

        $this->info("Notification cleanup completed!");
    }

    /**
     * Remove duplicate unread notifications
     */
    private function removeDuplicateNotifications()
    {
        $duplicatesRemoved = 0;

        // Get all unread notifications grouped by stock_id and type
        $notifications = Notification::where('read', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(['stock_id', 'notification_type']);

        foreach ($notifications as $stockId => $typeGroups) {
            foreach ($typeGroups as $type => $notificationGroup) {
                if ($notificationGroup->count() > 1) {
                    // Keep the most recent notification, delete the rest
                    $notificationsToDelete = $notificationGroup->slice(1);

                    foreach ($notificationsToDelete as $notification) {
                        $notification->delete();
                        $duplicatesRemoved++;
                    }
                }
            }
        }

        return $duplicatesRemoved;
    }

    /**
     * Mark resolved notifications as read
     */
    private function markResolvedNotificationsAsRead()
    {
        $resolvedCount = 0;

        // Get all unread notifications with their associated stocks
        $notifications = Notification::with('stock')
            ->where('read', false)
            ->get();

        foreach ($notifications as $notification) {
            if (!$notification->stock) {
                // Stock doesn't exist anymore, mark notification as read
                $notification->update(['read' => true]);
                $resolvedCount++;
                continue;
            }

            $stock = $notification->stock;
            $shouldMarkAsRead = false;

            // Check if notification conditions are no longer valid
            switch ($notification->notification_type) {
                case 'out_of_stock':
                    if ($stock->quantity > 0) {
                        $shouldMarkAsRead = true;
                    }
                    break;

                case 'low_stock':
                    if ($stock->quantity > 5) {
                        $shouldMarkAsRead = true;
                    }
                    break;

                case 'expiring_soon':
                    // If item is now expired or has more than 3 days left
                    $daysLeft = Carbon::parse($stock->expiration_date)->diffInDays(Carbon::now());
                    if (Carbon::parse($stock->expiration_date)->isPast() || $daysLeft > 3) {
                        $shouldMarkAsRead = true;
                    }
                    break;

                case 'expired':
                    // Keep expired notifications as they remain relevant
                    break;

                default:
                    // For general notifications, check if they're older than 7 days
                    if ($notification->created_at->lt(Carbon::now()->subDays(7))) {
                        $shouldMarkAsRead = true;
                    }
                    break;
            }

            if ($shouldMarkAsRead) {
                $notification->update(['read' => true]);
                $resolvedCount++;
            }
        }

        return $resolvedCount;
    }
}
