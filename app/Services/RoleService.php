<?php
namespace App\Services;

use App\Models\AuthGroup;
use Lauthz\Facades\Enforcer;

class RoleService
{
    public function getRoleTree($parentId = 0, $all = null)
    {
        if (is_null($all)) {
            $all = AuthGroup::all();
        }

        return $all
            ->where('parent_id', $parentId)
            ->map(function (AuthGroup $authGroup) use ($all) {
                $data = $authGroup->toArray();
                if ($authGroup->type == AuthGroup::TYPE_OPERATE) {
                    return $data;
                }
                $data['children'] = $this->getRoleTree($authGroup->id, $all)->merge([])->toArray();
                return $data;
            });
    }

    public function getRoleAuthGroupIds($auth_str)
    {
        $auth_group = Enforcer::guard('admin')->GetImplicitPermissionsForUser($auth_str);
        $rule = collect($auth_group)->pluck(1);
        $action = collect($auth_group)->pluck(2);

        $list = AuthGroup::whereIn('rule' , $rule)->whereIn('action', $action)->get();
        return $list;
    }
}
