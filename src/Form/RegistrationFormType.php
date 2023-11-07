<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d entrer un nom d utilisateur',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => '
                        Votre nom d utilisateur doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => '
                        Votre nom d utilisateur ne doit pas dépasser {{ limit }} caractères',
                    ]),
                    // Ajoutez ici d'autres contraintes, si nécessaire
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'label' => 'Accepter les conditions', // Changez le texte du label ici
            ])
            
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[#?!@$%^&*-]).{14,}$/',

                        'message' => 'Il faut un mot de passe de 14 caractères avec au moins 1 majuscule, 1 minuscule, 1 chiffre, et 1 caractère spécial.',
                    ]),
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
