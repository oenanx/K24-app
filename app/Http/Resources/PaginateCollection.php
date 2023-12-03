<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateCollection extends ResourceCollection
{


    public function __construct($resource)
    {
        parent::__construct($resource);
        //dd($this);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        $sort = ($sorts = $request->post('sort')) ? $sorts['sort'] : null;

        return [
            'meta' => [
                'pages' => $this->resource->Total(),
                'total' => $this->resource->Total(),
                'perpage' => $this->resource->PerPage(),
                'page' => $this->resource->CurrentPage(),
                'sort' => $sort,
                'field' => $sorts ? $sorts['field'] : null,
            ],
            'data' => $this->collection
        ];

        //return parent::toArray($request);
    }


    public function with($request): array
    {
        return [
            'meta' => [
                'key' => 'value',
            ],
        ];
    }
}
