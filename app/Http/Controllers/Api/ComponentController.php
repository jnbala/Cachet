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

use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\Tag;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ComponentController extends AbstractApiController
{
    /**
     * Get all components.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \CachetHQ\Cachet\Models\Component         $component
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getComponents(Request $request, Component $component)
    {
        $components = $component->paginate(Binput::get('per_page', 20));

        return $this->paginator($components, $request);
    }

    /**
     * Get a single component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function getComponent(Component $component)
    {
        return $this->item($component);
    }

    /**
     * Create a new component.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function postComponents(Guard $auth, Component $component)
    {
        $componentData = Binput::except('tags');
        $componentData['user_id'] = $auth->user()->id;

        $component = $component->create($componentData);

        if ($component->isValid()) {
            if (Binput::has('tags')) {
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

        throw new BadRequestHttpException();
    }

    /**
     * Update an existing component.
     *
     * @param \CachetHQ\Cachet\Models\Componet $component
     *
     * @return \CachetHQ\Cachet\Models\Component
     */
    public function putComponent(Component $component)
    {
        $component->update(Binput::except('tags'));

        if ($component->isValid('updating')) {
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

        throw new BadRequestHttpException();
    }

    /**
     * Delete an existing component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComponent(Component $component)
    {
        $component->delete();

        return $this->noContent();
    }
}
