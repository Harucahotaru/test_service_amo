<?php

namespace App\Form;

use App\Entity\Contact;
use App\Service\AmoCrmApi\CustomFields\SexEnums;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('birthday', BirthdayType::class)
            ->add('phone', TextType::class, [
                'constraints' => [new Regex('/^\+?[0-9]+$/')],
            ])
            ->add('email', EmailType::class)
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    SexEnums::MALE->value => SexEnums::MALE->name,
                    SexEnums::FEMALE->value => SexEnums::FEMALE->name,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
