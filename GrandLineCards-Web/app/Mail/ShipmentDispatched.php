<?php

namespace App\Mail;

use App\Models\CustomerShipment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentDispatched extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomerShipment $shipment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu pedido de Grand Line Cards ha sido enviado',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.marketplace.shipment_dispatched',
        );
    }
}
