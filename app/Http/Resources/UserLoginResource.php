<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
