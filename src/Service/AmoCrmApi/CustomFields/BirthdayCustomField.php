<?php

namespace App\Service\AmoCrmApi\CustomFields;

use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\DateCustomFieldModel;

class BirthdayCustomField implements CustomFieldInterface
{
    public const CODE = 'BIRTHDAY';
    public const NAME = 'День рождения';

    public function create(): CustomFieldModel
    {
        return (new DateCustomFieldModel())
            ->setName(self::NAME)
            ->setCode(self::CODE);
    }
}
