<?php

namespace App\Service\AmoCrmApi\CustomFields;

use Exception;

class CustomFieldsFactory
{
    /**
     * Custom types.
     */
    private const TYPES = [
        SexCustomField::CODE => SexCustomField::class,
        BirthdayCustomField::CODE => BirthdayCustomField::class,
    ];

    /**
     * @throws Exception
     */
    public function getField(string $code): CustomFieldInterface
    {
        if (isset(self::TYPES[$code])) {
            return new (self::TYPES[$code]);
        }

        throw new Exception('Unknown custom field code');
    }
}
