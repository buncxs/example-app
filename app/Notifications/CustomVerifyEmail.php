<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class CustomVerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirma tu correo de la aplicacion ' . config('app.name'))
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line('Por favor haz click en el boton de abajo para verificar tu correo')
            ->action('Verificar Dirección de Correo', $this->verificationUrl($notifiable))
            ->line('Si tu no creaste esta cuenta, ignora este mensaje');
    }

}
