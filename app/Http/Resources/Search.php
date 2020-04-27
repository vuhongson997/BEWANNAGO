<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\searchResource;
class Search extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private $pagination;

    public function __construct($resource)
    {
        $this->total =  $resource->total();
            
        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
    public function toArray($request)
    {
        return [
            'result'=>searchResource::collection($this->collection),
            'totalCount'=>$this->total
        ];
    }
}
