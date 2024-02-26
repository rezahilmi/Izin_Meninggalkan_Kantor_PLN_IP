<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class MailSuratIzin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pegawai;
    public $id;

    public function __construct($pegawai, $id)
    {
        $this->pegawai = $pegawai;
        $this->id = $id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Izin Meninggalkan Kantor',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return (new Content(
            view: 'emails.surat_izin'
        ))->with(['pegawai' => $this->pegawai,'id' => $this->id]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $folderPath = public_path('surat izin keluar kantor');
        $filePath = $folderPath . '/surat_izin_' . $this->id . '.docx';
        return [
            Attachment::fromPath($filePath)
                    ->as('surat_izin_' . $this->id . '.docx')
                    ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ];
    }
}
