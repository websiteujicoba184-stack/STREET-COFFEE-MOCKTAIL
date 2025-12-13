<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $token;
    public $kodePegawai;

    public function __construct($name, $token, $kodePegawai = null)
    {
        $this->name = $name;
        $this->token = $token;
        $this->kodePegawai = $kodePegawai;
    }

    public function build()
    {
        $verifyUrl = url('/verify?token=' . $this->token);

        $subject = $this->kodePegawai
            ? 'Verifikasi & Kode Pegawai - Street Coffee Mocktail'
            : 'Verifikasi Akun Street Coffee Mocktail';

        return $this->subject($subject)
            ->html("
                <h3>Halo {$this->name},</h3>
                <p>Terima kasih telah mendaftar di <b>Street Coffee Mocktail</b>!</p>
                " . ($this->kodePegawai ? "<p>Kode Pegawai Anda adalah: <b>{$this->kodePegawai}</b></p>" : "") . "
                <p>Klik tautan di bawah ini untuk verifikasi akun Anda:</p>
                <p><a href='{$verifyUrl}' target='_blank'>Verifikasi Sekarang</a></p>
                <p>Salam hangat,<br>Street Coffee Mocktail ☕</p>
            ");
    }
}
