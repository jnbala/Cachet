<?php

namespace CachetHQ\Cachet\Handlers\Events;

use CachetHQ\Cachet\Events\CustomerHasSubscribedEvent;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Mail\Message;

class SendSubscriberVerificationEmailHandler implements ShouldBeQueued
{
    /**
     * The mailer instance.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * Create a new send subscriber verification email handler.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the customer has subscribed event.
     *
     * @param \CachetHQ\Cachet\Events\CustomerHasSubscribedEvent $event
     *
     * @return void
     */
    public function handle(CustomerHasSubscribedEvent $event)
    {
        $subscriber = $event->getSubscriber();

        $mail = [
            'email'     => $subscriber->email,
            'subject'   => 'Confirm your subscription.',
            'link'      => route('subscribe-verify', $subscriber->verify_code),
        ];

        $this->mailer->send([
            'html' => 'emails.subscribers.verify-html',
            'text' => 'emails.subscribers.verify-text',
        ], $mail, function (Message $message) use ($mail) {
            $message->to($mail['email'])->subject($mail['subject']);
        });
    }
}
