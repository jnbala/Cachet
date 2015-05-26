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

use CachetHQ\Cachet\Models\MetricPoint;
use GrahamCampbell\Binput\Facades\Binput;

class MetricPointController extends AbstractApiController
{
    /**
     * Get a single metric point.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\MetricPoint
     */
    public function getMetricPoints($id)
    {
        return $this->item($this->metricPoint->findOrFail($id));
    }

    /**
     * Create a new metric point.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\MetricPoint
     */
    public function postMetricPoints($id)
    {
        return $this->item($this->metricPoint->create($id, Binput::all()));
    }

    /**
     * Updates a metric point.
     *
     * @param int $metricId
     * @param int $pointId
     *
     * @return \CachetHQ\Cachet\Models\MetricPoint
     */
    public function putMetricPoint($metricId, $pointId)
    {
        $metricPoint = $this->metricPoint->findOrFail($pointId);
        $metricPoint->update(Binput::all());

        return $this->item($metricPoint);
    }

    /**
     * Destroys a metric point.
     *
     * @param int $metricId
     * @param int $pointId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function deleteMetricPoint($metricId, $pointId)
    {
        $this->metricPoint->destroy($pointId);

        return $this->noContent();
    }
}
