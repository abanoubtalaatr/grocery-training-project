<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    protected ?string $token = null;
    protected bool $includeVerificationFields = false;

    /**
     * Attach a token payload to the user data response.
     */
    public function withToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Specify whether to output email/phone verified state tags.
     */
    public function includeVerification(bool $include = true): self
    {
        $this->includeVerificationFields = $include;
        return $this;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $userPayload = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->includeVerificationFields) {
            $userPayload['email_verified'] = $this->email_verified;
            $userPayload['phone_verified'] = $this->phone_verified;
        }

        if ($this->includeVerificationFields || !$this->token) {
            $userPayload['created_at'] = $this->created_at;
        }

        $data = [
            'user' => $userPayload,
        ];

        if ($this->token !== null) {
            $data['token'] = $this->token;
        }

        return $data;
    }
}
