<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserLoginResource extends JsonResource
{
    protected User $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $data =  [
            'token' => $this->user->createToken($request->ip())->plainTextToken,
            'user' => $this->user->toArray(),
        ];
        return $data;
    }
}
