<?php

namespace App\Mail\Cart;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcceptOrder extends Mailable
{
    use Queueable, SerializesModels;

    public mixed $order;
    public string $message;
    public string $subjectMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(mixed $order, string $message, string $subjectMessage)
    {
        $this->order = $order;
        $this->message = $message;
        $this->subjectMessage = $subjectMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectMessage,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.cart.accept-order',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
