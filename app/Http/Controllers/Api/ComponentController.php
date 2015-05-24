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

use CachetHQ\Cachet\Models\Tag;
use CachetHQ\Cachet\Models\Component;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class ComponentController extends AbstractApiController
{
    /**
     * The component repository instance.
     *
     * @var \CachetHQ\Cachet\Models\Component
     */
    protected $component;

    /**
     * Create a new component controller instance.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     */
    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    /**
     * Get all components.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getComponents(Request $request)
    {
        $components = $this->component->paginate(Binput::get('per_page', 20));

        return $this->paginator($components, $request);
    }

    /**
     * Get a single component.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function getComponent($id)
    {
        return $this->item($this->component->findOrFail($id));
    }

    /**
     * Create a new component.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function postComponents(Guard $auth)
    {
        $componentData = Binput::except('tags');
        $componentData['user_id'] = $auth->user()->id;

        $component = $this->component->create($componentData);

        if ($component->isValid() && Binput::has('tags')) {
            // The component was added successfully, so now let's deal with the tags.
            $tags = preg_split('/ ?, ?/', Binput::get('tags'));

            // For every tag, do we need to create it?
            $componentTags = array_map(function ($taggable) use ($component) {
                return Tag::firstOrCreate([
                    'name' => $taggable,
                ])->id;
            }, $tags);

            $component->tags()->sync($componentTags);
        }

        return $this->item($component);
    }

    /**
     * Update an existing component.
     *
     * @param int $id
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function putComponent($id)
    {
        $component = $this->component->update($id, Binput::except('tags'));

        if (Binput::has('tags')) {
            $tags = preg_split('/ ?, ?/', Binput::get('tags'));

            // For every tag, do we need to create it?
            $componentTags = array_map(function ($taggable) use ($component) {
                return Tag::firstOrCreate([
                    'name' => $taggable,
                ])->id;
            }, $tags);

            $component->tags()->sync($componentTags);
        }

        return $this->item($component);
    }

    /**
     * Delete an existing component.
     *
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function deleteComponent($id)
    {
        $this->component->destroy($id);

        return $this->noContent();
    }
}
