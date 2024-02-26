<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class persetujuanIzin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pegawai;
    public $atasan;

    public function __construct($pegawai,$atasan)
    {
        $this->pegawai = $pegawai;
        $this->atasan = $atasan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permohonan Izin Meninggalkan Kantor',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return (new Content(
            view: 'emails.persetujuan_izin'
        ))->with(['pegawai' => $this->pegawai,'atasan' => $this->atasan]);
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
