<?php

namespace App\Helpers;

enum RolesEnum: string
{

    case ADMIN_ROLE = 'admin';


    public function label(): string
    {
        return match ($this) {
            self::ADMIN_ROLE => 'Administrateur',
        };
    }
}
