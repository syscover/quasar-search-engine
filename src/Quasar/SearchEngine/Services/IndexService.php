<?php namespace Quasar\SearchEngine\Services;

use Quasar\Core\Services\CoreService;
use Quasar\SearchEngine\Models\Index;

class IndexService extends CoreService
{
    private $permissionsIndexed = [
        '205e2c88-2254-45d2-91df-615ca95983ac', // cms.article.get
        'dab3dcf7-e119-4b72-8498-99cf3843ca9c', // admin.user.get
    ];

    public function create(array $data)
    {
        $this->validate($data, [
            'uuid'              => 'nullable|uuid',
            'permissionUuid'    => 'nullable|uuid|exists:admin_permission,uuid',
            'url'               => 'required|between:2,511',
            'title'             => 'nullable|between:2,511',
            'contentLayer1'     => 'nullable',
            'contentLayer2'     => 'nullable',
            'contentLayer3'     => 'nullable'
        ]);
        
        //
        $data = $this->cleanData($data);

        return Index::create($data)->fresh();
    }

    public function update(array $data, string $uuid)
    {
        $this->validate($data, [
            'uuid'              => 'nullable|uuid',
            'permissionUuid'    => 'nullable|uuid|exists:admin_permission,uuid',
            'url'               => 'required|between:2,511',
            'title'             => 'nullable|between:2,511',
            'contentLayer1'     => 'nullable',
            'contentLayer2'     => 'nullable',
            'contentLayer3'     => 'nullable'
        ]);

        //
        $data = $this->cleanData($data);

        $object = Index::where('uuid', $uuid)->first();

        // fill data
        $object->fill($data);

        // save changes
        $object->save();

        return $object;
    }

    public function search(string $query)
    {
        // get permissions form user
        $permissionsUuid = accessToken()->user->permissions()->whereIn('admin_permission.uuid', $this->permissionsIndexed)->get()->pluck('uuid');

        // search
        $response = Index::search($query)->raw();
        
        // filter responses according permissions
        $response['results'] = collect($response['results'])->filter(function($value, $key) use ($permissionsUuid) {
                return $permissionsUuid->contains($value['permissionUuid']) || $value['permissionUuid'] === null;
            });
        $response['count'] = count($response['results']);

        return $response;
            
    }

    private function cleanData(array $data)
    {
        foreach ($data as $key => $value)
        {
            if ($key === 'title' || $key === 'contentLayer1' || $key === 'contentLayer2' || $key === 'contentLayer3')
            {
                $data[$key] = strip_tags($data[$key]);
            }
        }

        return $data;
    }
}
