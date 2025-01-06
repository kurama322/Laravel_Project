<?php

namespace App\Enums\Permissions;

enum UserEnum: string
{
    case  EDIT = 'edit user';
    case DELETE = 'delete user';
    case PUBLISH = 'publish user';

    public static function values(): array
    {
        $values = [];

        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}
