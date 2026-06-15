<?php

namespace App\Actions\Profile;

use App\Exceptions\BusinessException;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DestroySessionAction
{
    use AsAction;

    /**
     * Handle revoking a personal access token/session.
     *
     * @throws BusinessException
     */
    public function handle(User $user, string $tokenId): void
    {
        $currentTokenId = $user->currentAccessToken()?->id;

        if ((string) $tokenId === (string) $currentTokenId) {
            throw new BusinessException('Cannot revoke your current session from this request. Use logout instead.', 400);
        }

        $token = $user->tokens()->find($tokenId);
        if (!$token) {
            throw new BusinessException('Session not found', 404);
        }

        $token->delete();
    }
}
