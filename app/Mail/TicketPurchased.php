<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('items.ticketZone.ticket', 'items.tokens', 'user');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Tiket PRJ 2026 - ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-purchased',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}