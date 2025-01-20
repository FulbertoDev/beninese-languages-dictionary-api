<?php

namespace App\Helpers;

enum PermissionsEnum: string
{

    case CAN_ADD_WORD = "can-add-word";
    case CAN_EDIT_WORD = "can-edit-word";
    case CAN_DELETE_WORD = "can-delete-word";
    case CAN_MANAGE_USERS = "can-manage-users";

    public function label(): string
    {
        return match ($this) {
            self::CAN_ADD_WORD => 'Ajout de mot',
            self::CAN_MANAGE_USERS => 'Gérer les utilisateurs',
            self::CAN_EDIT_WORD => 'Modifier un mot',
            self::CAN_DELETE_WORD => 'Supprimer un mot',
        };
    }
}
