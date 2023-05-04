<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Aktualne hasło',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'current-password',
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być takie same.',
                'mapped' => false,
                'first_options' => [
                    'label' => 'Nowe hasło',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'id' => 'newPassword',
                    ],
                ],
                'second_options' => [
                    'label' => 'Powtórz nowe hasło',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'id' => 'newPasswordRepeat',
                    ],
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Hasło musi mieć co najmniej {{ limit }} znaków.',
                        'max' => 4096,
                    ]),
                    new NotBlank([
                        'message' => 'Proszę podać hasło.',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień hasło',
                'attr' => [
                    'class' => 'btn btn-outline-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
