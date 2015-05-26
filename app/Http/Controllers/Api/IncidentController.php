<?php

/*
 * This file is part of Cachet.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Http\Controllers\Api;

use CachetHQ\Cachet\Models\Incident;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IncidentController extends AbstractApiController
{
    /**
     * The incident repository instance.
     *
     * @var \CachetHQ\Cachet\Models\Incident
     */
    protected $incident;

    /**
     * Create a new incident controller instance.
     *
     * @param \CachetHQ\Cachet\Models\Incident $incident
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * Get all incidents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getIncidents(Request $request)
    {
        $incidents = $this->incident->paginate(Binput::get('per_page', 20));

        return $this->paginator($incidents, $request);
    }

    /**
     * Get a single incident.
     *
     * @param \CachetHQ\Cachet\Models\Incident $incident
     *
     * @return \CachetHQ\Cachet\Models\Incident
     */
    public function getIncident(Incident $incident)
    {
        return $this->item($incident);
    }

    /**
     * Create a new incident.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     *
     * @return \CachetHQ\Cachet\Models\Incident
     */
    public function postIncidents(Guard $auth)
    {
        $incidentData = Binput::all();
        $incidentData['user_id'] = $auth->user()->id;
        $incident = Incident::create($incidentData);

        if ($incident->isValid()) {
            return $this->item($incident);
        }

        throw new BadRequestHttpException();
    }

    /**
     * Update an existing incident.
     *
     * @param \CachetHQ\Cachet\Models\Inicdent $incident
     *
     * @return \CachetHQ\Cachet\Models\Incident
     */
    public function putIncident(Incident $incident)
    {
        $incident->update(Binput::all());

        if ($incident->isValid('updating')) {
            return $this->item($incident);
        }

        throw new BadRequestHttpException();
    }

    /**
     * Delete an existing incident.
     *
     * @param \CachetHQ\Cachet\Models\Inicdent $incident
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteIncident(Incident $incident)
    {
        $incident->delete();

        return $this->noContent();
    }
}
