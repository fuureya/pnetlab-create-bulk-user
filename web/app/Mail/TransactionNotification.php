<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $statusLabel;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\Transaction $transaction)
    {
        $this->transaction = $transaction;

        if ($transaction->status === 'success') {
            $this->statusLabel = 'Berhasil';
        } elseif ($transaction->status === 'pending') {
            $this->statusLabel = 'Menunggu Pembayaran';
        } else {
            $this->statusLabel = 'Gagal/Kedaluwarsa';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Pembayaran Meraki Labs: ' . $this->statusLabel,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
