<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre', ChoiceType::class,[
                'attr' => ['class' => 'text-dark fs-5',
                            'type' => 'radio'],
                'choices' => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame'
                ],
                'expanded' => true,
            ])
            ->add('email',EmailType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('username',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_naissance', DateType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('temps_reponse',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresse',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                           'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
