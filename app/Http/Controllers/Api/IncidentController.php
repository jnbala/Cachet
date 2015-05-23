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
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Incident
     */
    public function getIncident($id)
    {
        return $this->item($this->incident->findOrFail($id));
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
        return $this->item($this->incident->create($auth->user()->id, Binput::all()));
    }

    /**
     * Update an existing incident.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Incident
     */
    public function putIncident($id)
    {
        return $this->item($this->incident->update($id, Binput::all()));
    }

    /**
     * Delete an existing incident.
     *
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function deleteIncident($id)
    {
        $this->incident->destroy($id);

        return $this->noContent();
    }
}
