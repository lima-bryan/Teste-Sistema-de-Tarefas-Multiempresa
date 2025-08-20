<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /*
     Queueable: permite que o envio do e-mail seja em uma fila em 2º plano
     SerializesModels: garante que a tarefa seja enviada de forma eficiente para a fila

    public $task:pra receber a tarefa que será notificada
    public $messageText: para receber o texto da mensagem que será enviada

     */

    use Queueable,SerializesModels;

    public $task; 
    public $messageText;

    public function __construct(Task $task, $messageText)
    {
        $this->task = $task; // atribui a tarefa que será notificada
        $this->messageText = $messageText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Notificação de Tarefa') //define o assunto do email
            ->view('emails.task_notification'); //define a view que será usada para o email;
    }
}
