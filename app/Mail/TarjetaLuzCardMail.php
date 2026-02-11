<?php

namespace App\Mail;

use App\Models\RegistroAfiliado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TarjetaLuzCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $afiliado;
    public $rutaTarjetaImagen;

    /**
     * Create a new message instance.
     */
    public function __construct(RegistroAfiliado $afiliado, $rutaTarjetaImagen = null)
    {
        $this->afiliado = $afiliado;
        $this->rutaTarjetaImagen = $rutaTarjetaImagen;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 ¡Bienvenido a LUZCARD! - Tu Tarjeta Digital',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tarjeta-luzcard',
            with: [
                'nombre' => $this->afiliado->Afiliado_Nombres,
                'dni' => $this->afiliado->Afiliado_DNI,
                'fechaRegistro' => \Carbon\Carbon::parse($this->afiliado->Fecha_Registro)->format('d/m/Y'),
                'fechaVigencia' => $this->afiliado->fecha_fin_vigencia 
                    ? \Carbon\Carbon::parse($this->afiliado->fecha_fin_vigencia)->format('d/m/Y')
                    : \Carbon\Carbon::parse($this->afiliado->Fecha_Registro)->addYear()->format('d/m/Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        // Adjuntar imagen de la tarjeta si existe
        if ($this->rutaTarjetaImagen && file_exists($this->rutaTarjetaImagen)) {
            $attachments[] = Attachment::fromPath($this->rutaTarjetaImagen)
                ->as('TarjetaLuzCard_' . $this->afiliado->Afiliado_DNI . '.png')
                ->withMime('image/png');
        }

        // Adjuntar el contrato PDF si existe
        if ($this->afiliado->Ruta_Contrato && Storage::disk('public')->exists($this->afiliado->Ruta_Contrato)) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->afiliado->Ruta_Contrato)
                ->as('Contrato_LuzCard_' . $this->afiliado->Afiliado_DNI . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}