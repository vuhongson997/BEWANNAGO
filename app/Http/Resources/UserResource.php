<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\UserResource1;
class UserResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
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
            'result'=>UserResource1::collection($this->collection),
            'totalCount'=>$this->total
        ];
    }
}