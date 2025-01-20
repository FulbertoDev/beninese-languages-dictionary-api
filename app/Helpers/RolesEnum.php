<?php

namespace App\Helpers;

enum RolesEnum: string
{

    case ADMIN_ROLE = 'admin';
    case HELPER_ROLE = 'helper';


    public function label(): string
    {
        return match ($this) {
            self::ADMIN_ROLE => 'Administrator',
            self::HELPER_ROLE => 'Volunteer',
        };
    }
}
