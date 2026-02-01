<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeVerifyEmail extends VerifyEmail
{
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('¡Bienvenido a Grand Line Cards! Confirma tu cuenta')
            ->greeting('¡Hola Capitán!')
            ->line('Gracias por unirte a la tripulación de Grand Line Cards. Estamos emocionados de tenerte a bordo.')
            ->line('Para comenzar tu aventura y acceder a todas las funciones, por favor verifica tu dirección de correo electrónico.')
            ->action('Verificar Correo y Zarpar', $url)
            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje tranquilamente.')
            ->salutation('Nos vemos en el mar, El equipo de Grand Line Cards');
    }
}
