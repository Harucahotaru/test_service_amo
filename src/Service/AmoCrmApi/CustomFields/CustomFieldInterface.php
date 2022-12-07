<?php

namespace App\Service\AmoCrmApi\CustomFields;

use AmoCRM\Models\CustomFields\CustomFieldModel;

interface CustomFieldInterface
{
    public function create(): CustomFieldModel;
}
