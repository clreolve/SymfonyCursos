<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => USER::MSG_CORREO_REQUERIDO])
                ]
            ])
            //->add('roles')
            ->add(
                'password',
                PasswordType::class,
                [
                    'constraints' => [
                        new NotBlank(['message' => USER::MSG_PASSWORD_REQUERIDO]),
                        new Length([
                            'min' => 4,
                            'max' => 40,
                            'minMessage' => 'Tu contraseña debe tener minimo 4 caracteres',
                            'maxMessage' => 'Tu contraseña debe tener maximo 40 caracteres '
                        ])
                    ]
                ]
            )
            //->add('activo')
            ->add('nombre')
            ->add('apellido')
            ->add('tipo', ChoiceType::class, [
                'choices'  => [
                    'Creador' => 'creador',
                    'Consumidor' => 'consumidor'
                ],
            ])
            ->add('Registrar', SubmitType::class) //boton de registrar
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
