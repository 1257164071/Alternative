<?php
namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Models\AuthGroup;
use App\Models\Role;

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
                $data['children'] = $this->getRoleTree($authGroup->id, $all)->toArray();
                if ($data['children'] != null){
                    $i = 0;
                    foreach ($data['children'] as $key => $val) {
                        $data['children'][$i++] = $val;
                        unset($data['children'][$key]);
                    }
                }
                return $data;
            });
    }

    public function authorize($role, $rule)
    {
        if (!$role instanceof Role){
            $role = Role::find($role);
        }
        if ($role == null) {
            throw new InvalidRequestException('角色不存在');
        }

        $flag = $role->auth_groups()->where('rule', $rule)->exists();

        if ($flag == false){
            throw new InvalidRequestException('没有权限');
        }
        return true;
    }


}
