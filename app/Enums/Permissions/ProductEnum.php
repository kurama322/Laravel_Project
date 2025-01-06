<?php

namespace App\Enums\Permissions;

enum ProductEnum: string
{
    case  EDIT = 'edit product';
    case DELETE = 'delete product';
    case PUBLISH = 'publish product';

    public static function values(): array
    {
        $values = [];

        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }

}
