<?php

namespace App\Mail;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ItemSold extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OrderItem $orderItem
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Has vendido una carta en Grand Line Cards!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.marketplace.item_sold',
        );
    }
}
