<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $type,
        public readonly string $message,
        public readonly ?int   $refId = null,
    ){}


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'message' => $this->message,
            'ref_id'  => $this->refId,
            'icon'    => $this->getIcon(),
        ];
    }

    private function getIcon(): string
    {
        return match($this->type) {
            'order_created'    => '🧾',
            'refund_request'   => '🔄',
            'refund_approved'  => '✅',
            'refund_rejected'  => '❌',
            'review_approved'  => '⭐',
            'review_rejected'  => '🚫',
            'ticket_generated' => '🎫',
            'waitlist_joined'  => '📋',
            'points_redeemed'  => '🎁',
            'new_refund_admin' => '🔔',
            'support_new'      => '💬',
            'support_reply'    => '↩️',
            'support_answered' => '💬',
            'support_closed'   => '🔒',
            default            => '🔔',
        };
    }
}
