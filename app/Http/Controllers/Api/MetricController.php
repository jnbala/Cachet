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

use CachetHQ\Cachet\Models\Metric;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Http\Request;

class MetricController extends AbstractApiController
{
    /**
     * The metric repository instance.
     *
     * @var \CachetHQ\Cachet\Models\Metric
     */
    protected $metric;

    /**
     * Create a new metric controller instance.
     *
     * @param \CachetHQ\Cachet\Models\Metric $metric
     */
    public function __construct(Metric $metric)
    {
        $this->metric = $metric;
    }

    /**
     * Get all metrics.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMetrics(Request $request)
    {
        $metrics = $this->metric->paginate(Binput::get('per_page', 20));

        return $this->paginator($metrics, $request);
    }

    /**
     * Get a single metric.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Metric
     */
    public function getMetric($id)
    {
        return $this->item($this->metric->findOrFail($id));
    }

    /**
     * Get all metric points.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMetricPoints($id)
    {
        return $this->collection($this->metric->points($id));
    }

    /**
     * Create a new metric.
     *
     * @return \CachetHQ\Cachet\Models\Metric
     */
    public function postMetrics()
    {
        return $this->item($this->metric->create(Binput::all()));
    }

    /**
     * Update an existing metric.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Metric
     */
    public function putMetric($id)
    {
        return $this->item($this->metric->update($id, Binput::all()));
    }

    /**
     * Delete an existing metric.
     *
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function deleteMetric($id)
    {
        $this->metric->destroy($id);

        return $this->noContent();
    }
}
