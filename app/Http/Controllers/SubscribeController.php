<?php

/*
 * This file is part of Cachet.
 *
 * (c) James Brooks <james@cachethq.io>
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Http\Controllers;

use CachetHQ\Cachet\Events\CustomerHasSubscribedEvent;
use CachetHQ\Cachet\Facades\Setting;
use CachetHQ\Cachet\Models\Subscriber;
use Carbon\Carbon;
use GrahamCampbell\Binput\Facades\Binput;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscribeController extends AbstractController
{
    /**
     * Show the subscribe by email page.
     *
     * @return \Illuminate\View\View
     */
    public function showSubscribe()
    {
        return View::make('subscribe', [
            'pageTitle' => Setting::get('app_name'),
            'aboutApp'  => Markdown::convertToHtml(Setting::get('app_about')),
        ]);
    }

    /**
     * Handle the subscribe user.
     *
     * @return \Illuminate\View\View
     */
    public function postSubscribe()
    {
        $subscriber = Subscriber::create(['email' => Binput::get('email')]);

        if (!$subscriber->isValid()) {
            segment_track('Subscribers', [
                'event'   => 'User Subscribed',
                'success' => false,
            ]);

            return Redirect::back()->withInput(Binput::all())
                ->with('title', sprintf(
                    '<strong>%s</strong> %s',
                    trans('dashboard.notifications.whoops'),
                    trans('dashboard.components.add.failure')
                ))
                ->with('errors', $subscriber->getErrors());
        }

        segment_track('Subscribers', [
            'event'   => 'User Subscribed',
            'success' => true,
        ]);

        $successMsg = sprintf(
            '<strong>%s</strong> %s',
            trans('dashboard.notifications.awesome'),
            trans('dashboard.components.add.success')
        );

        event(new CustomerHasSubscribedEvent($subscriber));

        return Redirect::route('status-page')->with('success', $successMsg);
    }

    /**
     * Handle the verify subscriber email.
     *
     * @param string $token
     *
     * @return \Illuminate\View\View
     */
    public function getVerify($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        $subscriber = Subscriber::where('verify_code', '=', $token)->first();

        if (!$subscriber || $subscriber->verified()) {
            return Redirect::route('status-page');
        }

        $subscriber->verified_at = Carbon::now();
        $subscriber->save();

        segment_track('Subscribers', [
            'event'   => 'User Email Verified',
            'success' => true,
        ]);

        $successMsg = sprintf(
            '<strong>%s</strong> %s',
            trans('dashboard.notifications.awesome'),
            trans('dashboard.components.add.success')
        );

        return Redirect::route('status-page')->with('success', $successMsg);
    }
}
