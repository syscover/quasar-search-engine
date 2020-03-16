<?php namespace Quasar\SearchEngine\Services;

use Illuminate\Support\Facades\DB;
use Quasar\Core\Services\CoreService;
use Quasar\SearchEngine\Models\Index;

class IndexService extends CoreService
{
    public function create(array $data)
    {
        $this->validate($data, [
            'uuid'              => 'nullable|uuid',
            'permissionUuid'    => 'nullable|uuid|exists:admin_permission,uuid',
            'url'               => 'required|between:2,255',
            'title'             => 'nullable|between:2,255',
            'headers'           => 'nullable|between:2,255',
            'strong'            => 'nullable|between:2,255',
            'content'           => 'required'
        ]);

        return Index::create($data)->fresh();
    }

    public function update(array $data, string $uuid)
    {
        $this->validate($data, [
            'id'        => 'required|integer',
            'uuid'      => 'required|uuid',
            'name'      => 'between:2,255',
            'isMaster'  => 'nullable|boolean',
        ]);

        $object = Role::where('uuid', $uuid)->first();

        // fill data
        $object->fill($data);

        DB::transaction(function () use ($data, &$object)
        {
            // save changes
            $object->save();

            // update permissions
            $object->permissions()->sync($data['permissionsUuid']);
        });

        return $object;
    }
}
