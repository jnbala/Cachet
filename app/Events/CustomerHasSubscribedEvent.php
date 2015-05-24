<?php

/*
 * This file is part of Cachet.
 *
 * (c) James Brooks <james@cachethq.io>
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Events;

use CachetHQ\Cachet\Models\Subscriber;
use Illuminate\Queue\SerializesModels;

class CustomerHasSubscribedEvent extends AbstractEvent
{
    use SerializesModels;

    /**
     * The customer who has subscribed.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber
     */
    protected $subscriber;

    /**
     * Create a new customer has subscribed event instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Get the customer who has subscribed.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
}
