<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
// use Illuminate\Contracts\Auth\UserProvider as User;
use Modules\Cms\Contracts\PanelContract;  // da usare Facades per separazione dei moduli
use Modules\LU\Services\ProfileService;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Facades\Profile as ProfileFacade;
use Nwidart\Modules\Facades\Module;

/**
 * Class XotBasePanelPolicy.
 */
abstract class XotBasePanelPolicy
{
    use HandlesAuthorization;

    /**
     * @param UserContract $user
     * @param string       $ability
     *
     * @return bool|null
     */
    // *
    public function before($user, $ability)
    {
        // *--- filament non ha le policy sui panel

        if (\is_object($user)) {
            $route_params = getRouteParameters();
            $profile = ProfileService::make()->get($user);

            if (isset($route_params['module'])) {
                $module = Module::find($route_params['module']);
                $module_name = '';
                if (null !== $module) {
                    $module_name = $module->getName();
                }
                $has_area = $profile->hasArea($module_name);
                if (! $has_area) {
                    return false;
                }
                // return $has_area && $profile->isSuperAdmin();
            }
            // this means that if you're superadmin the policy will always returns "true"

            if ($profile->isSuperAdmin()) {
                return true;
            }
        }
        // */
        /*
        if (\is_object($user)) {
            $route_params = getRouteParameters();
            if (isset($route_params['module'])) {
                $module = Module::find($route_params['module']);
                $module_name = '';
                if (null != $module) {
                    $module_name = $module->getName();
                }
                $has_area = ProfileFacade::hasArea($module_name);

                return $has_area && ProfileFacade::isSuperAdmin();
            }
            if (ProfileFacade::isSuperAdmin()) {
                return true;
            }
        }*/

        return null;
    }

    // */
    /*
    public function artisan(?UserContract $user, PanelContract $panel): bool {
        return false;
    }
    */

    public function home(?UserContract $userContract, PanelContract $panelContract): bool
    {
        if (inAdmin() && ! $userContract instanceof UserContract) {
            return false;
        }

        $route_params = $panelContract->getRouteParams();

        if (isset($route_params['module']) && $userContract instanceof UserContract) {
            $module = Module::find($route_params['module']);
            $module_name = '';
            if (null !== $module) {
                $module_name = $module->getName();
            }

            // $profile = ProfileService::make()->get($user);

            // return $profile->hasArea($module_name);
            $areas = \Auth::user()->areas
                ->sortBy('order_column');

            $modules = Module::getByStatus(1);
            $areas = $areas->filter(
                fn ($item): bool => \in_array($item->area_define_name, array_keys($modules), true)
            );

            return \is_object($areas->firstWhere('area_define_name', $module_name));
        }

        return true;
    }

    public function index(?UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    public function show(?UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    public function create(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    public function edit(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function update(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function store(UserContract $userContract, PanelContract $panelContract): bool
    {
        /*
        return $panel->isRevisionBy($user);
        non e' stato creato.. percio' sempre false
        */
        return true;
    }

    public function indexAttach(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    public function indexEdit(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    // test delle tabs
    public function index_edit(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    /**
     * @return false
     */
    public function updateTranslate(UserContract $userContract, PanelContract $panelContract): bool
    {
        return false; // update-translate di @can()
    }

    public function destroy(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function delete(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function restore(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function forceDelete(UserContract $userContract, PanelContract $panelContract): bool
    {
        return false;
    }

    public function detach(UserContract $userContract, PanelContract $panelContract): bool
    {
        return $panelContract->isRevisionBy($userContract);
    }

    public function clone(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     */
    public function viewAny(UserContract $userContract): bool
    {
        return true;
    }

    public function view(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }
}
