<?php

namespace App\Enums\Permissions;

enum OrderEnum:string

{

    case  EDIT = 'edit Order';
    case DELETE = 'delete Order';


    public static function values(): array
    {
        $values = [];

        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}

