<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;
    public string $ticketCode;
    public string $qrImageUrl;

    public function __construct(Transaction $transaction, string $ticketCode, string $qrImageUrl)
    {
        $this->transaction = $transaction;
        $this->ticketCode  = $ticketCode;
        $this->qrImageUrl  = $qrImageUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Anda - ' . ($this->transaction->event->title ?? 'AmikomEventHub'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }
}
