<?php

namespace App\Form;

use App\Entity\User;
use App\Service\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "label" => "Nom d'utilisateur",
                "constraints" => [new NotBlank(message: "Vous devez saisir un nom d'utilisateur.")]
            ])
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les deux mots de passe doivent correspondre.",
                "required" => true,
                "first_options" => ["label" => "Mot de passe"],
                "second_options" => ["label" => "Tapez le mot de passe à nouveau"]
            ])
            ->add('email', EmailType::class, [
                "label" => "Adresse email",
                "constraints" => [new NotBlank(message: "Vous devez saisir un adresse email")]
            ])
            ->add("roles", ChoiceType::class, [
                "choices" => Role::getRoles(),
                "label" => "Selectionnez un role",
                "expanded" => false,
                "multiple" => true
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
