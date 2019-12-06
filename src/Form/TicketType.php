<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TicketType.
 */
class TicketType extends AbstractType
{
    public const YEARS_IN_PAST = 100;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = carbon();

        $builder
            ->add('name', TextType::class, [
                'label' => 'ticket.name',
                ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'ticket.birth_date',
                'years' => range($date->year, $date->year - self::YEARS_IN_PAST),
            ])
            ->add('country', CountryType::class, [
                'label' => 'ticket.country',
                'preferred_choices' => [
                    'FR',
                ],
            ])
            ->add('reduceRate', CheckboxType::class, [
                'label' => 'ticket.reduce_rate',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'validation_groups' => [
                'reservation',
            ],
        ]);
    }
}
