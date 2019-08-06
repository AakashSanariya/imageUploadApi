<?php

namespace App\Listeners;

use App\Events\EmailSendEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Class ExampleListener
 * @package App\Listeners
 */
class EmailSendListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(EmailSendEvent $email)
    {
        $data['html'] = $email->mailData['data'];
        Mail::send('emails.email_render', $data, function ($message) use ($email){
            $message->from($email->mailData['fromEmail'], 'ImageUpload Api');
            $message->to($email->mailData['to']);
            $message->subject($email->mailData['subject']);
        });
    }
}
