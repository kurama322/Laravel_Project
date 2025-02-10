<?php

namespace App\Enums\Permissions;

enum CategoryEnum: string
{
    case  EDIT ='edit category';
    case DELETE = 'delete category';
    case PUBLISH = 'publish category';

    public static function values():array
    {
        $values = [];

        foreach (self::cases() as $case)
        {
            $values[] = $case->value;
        }
        return $values;
    }
}
