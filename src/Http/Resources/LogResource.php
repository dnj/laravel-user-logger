<?php

namespace dnj\UserLogger\Http\Resources;

use dnj\AAA\Http\Resources\UserResource;
use dnj\UserLogger\Http\Resources\Concerns\HasSummary;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
	use HasSummary;

	public function toArray($request)
	{
		$data = parent::toArray($request);
		if ($this->summary) {
			unset($data['properties']);
		} else {
			$data['user'] = UserResource::make($this->resource->user);
		}
		return $data;
	}
}
