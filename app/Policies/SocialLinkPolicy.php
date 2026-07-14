<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;

class SocialLinkPolicy
{
    public function delete(User $user, SocialLink $socialLink): bool
    {
        return $user->page?->id === $socialLink->page_id;
    }
}
