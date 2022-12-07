<?php

namespace App\Service\AmoCrmApi\CustomFields;

use AmoCRM\Collections\CustomFields\CustomFieldEnumsCollection;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFields\RadiobuttonCustomFieldModel;

class SexCustomField implements CustomFieldInterface
{
    public const CODE = 'SEX';

    private const NAME = 'Пол';

    public function create(): CustomFieldModel
    {
        $FieldsModel = (new RadiobuttonCustomFieldModel())
            ->setName(self::NAME)
            ->setCode(self::CODE);

        $EnumsCollection = new CustomFieldEnumsCollection();
        $EnumsCollection
            ->add(
                (new EnumModel())
                    ->setSort(1)
                    ->setCode(SexEnums::MALE->name)
                    ->setValue(SexEnums::MALE->value)
            )
            ->add(
                (new EnumModel())
                    ->setSort(2)
                    ->setCode(SexEnums::MALE->name)
                    ->setValue(SexEnums::MALE->value)
            );

        return $FieldsModel->setEnums($EnumsCollection);
    }
}
