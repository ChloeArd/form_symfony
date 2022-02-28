<?php

namespace App\Form;

use App\Entity\FakeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FakeEntityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder
            ->add('name', TextType::class, [
                "label" => "Votre nom",
                "data" => "Chloé",
                "disabled" => false,
                "required" => true,
                "empty_data" => "Anonymous",
                "mapped" => false,
                "trim" => false,
                "attr" => [
                    "class" => "ma-classe",
                    "data-test" => "ma-valeur",
                    "maxlength" => 20,
                    "minlength" => 3
                ],
                "label_attr" => [
                    "class" => "ma-class-label"
                ],
                "help" => "<p>Mon texte d'aide pour les utilisateurs</p>",
                "help_attr" => [
                    "class" => "class-aide-attr"
                ],
                "help_html" => true

            ])
            ->add('email', EmailType::class, [
                "label" => "Votre adresse-mail",
                "attr" => [
                    'placeholder' => "mail@example.org",
                    "class" => "css-email-class"
                ]
            ])
            ->add('sign', TextareaType::class, [
                "label" => "Propriété Sign",
                "attr" => [
                    "rows" => 10,
                    "cols" => 10
                ]
            ])
            ->add('age', IntegerType::class, [
                "rounding_mode" => \NumberFormatter::ROUND_HALFUP,
                "label" => "Votre âge"
            ])
            ->add('currency', MoneyType::class, [
                "label" => "Montant à appliquer",
                "currency" => "USD",
                "rounding_mode" => \NumberFormatter::ROUND_HALFUP
            ])
            ->add('price', NumberType::class, [
                "label" => "Votre prix",
                "rounding_mode" => \NumberFormatter::ROUND_HALFUP
            ])
            ->add('password', PasswordType::class, [
                "label" => "Votre mot de passe"
            ])
            ->add('url', UrlType::class, [
                "label" => "Votre lien",
                "help" => "Une URL valide commence toujours pas https://",
                "default_protocol" => "https://"
            ])
            ->add('userRange', RangeType::class, [
                "label" => "Sélectionnez une intervalle",
                "help" => "Intervalle de prix",
                "attr" => [
                    "min" => 10,
                    "max" => 52
                ]
            ])
            ->add('phone', TelType::class, [
                "label" => "Votre numéro de téléphone",
                "help" => "Format +33..."
            ])
            ->add('color', ColorType::class, [
                "label" => "Votre couleur préférée"
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FakeEntity::class,
        ]);
    }
}
