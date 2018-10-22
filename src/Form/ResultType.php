<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game', ChoiceType::class, [
                'label' => 'Game',
                'choices' => $options['data']['games'],
            ])
            ->add('first', ChoiceType::class, [
                'label' => 'Winner',
                'choices' => $options['data']['players'],
            ])
            ->add('second', ChoiceType::class, [
                'label' => 'Second place',
                'choices' => $options['data']['players'],
            ])
            ->add('third', ChoiceType::class, [
                'label' => 'Third place',
                'choices' => $options['data']['players'],
                'required' => false,
            ])
            ->add('fourth', ChoiceType::class, [
                'label' => 'Stuck in another timezone',
                'choices' => $options['data']['players'],
                'required' => false,
            ])
        ;
    }
}