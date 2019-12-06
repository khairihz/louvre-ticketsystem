<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('visit', DateType::class, [
                'widget' => 'single_text',
                'label' => 'booking.visit',
                'format' => 'dd/MM/yyyy',
                'required' => true,
            ])
            ->add('typeOfTicket', ChoiceType::class, [
                'choices' => [
                    'booking.day' => Booking::TYPE_OF_TICKET_DAY,
                    'booking.half_day' => Booking::TYPE_OF_TICKET_HALF_DAY,
                ],
                'label' => 'booking.type_of_ticket',
            ])
            ->add('numberOfTickets', ChoiceType::class, [
                'choices' => array_combine(
                    range(Booking::MINIMUM_NUMBER_OF_TICKETS, Booking::MAXIMUM_NUMBER_OF_TICKETS),
                    range(Booking::MINIMUM_NUMBER_OF_TICKETS, Booking::MAXIMUM_NUMBER_OF_TICKETS)
                ),
                'label' => 'booking.number_of_tickets',
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'booking.email_error',
                'required' => true,
                'first_options' => ['label' => 'booking.email'],
                'second_options' => ['label' => 'booking.repeat_mail'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                'booking',
            ],
        ]);
    }
}
