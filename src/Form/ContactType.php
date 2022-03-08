<?php

namespace App\Form;

use App\Entity\Contact;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{

    private string $env;

    public function __construct(ParameterBagInterface $parameterBag) {
        $this->env = $parameterBag->get('app.environnement');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs nom ne peut pas être vide !'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champs prénom ne peut pas être vide !'
                    ])
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Votre adresse mail',
                'constraints' => [
                    new Email([
                        'message' => "L'adresse mail n'est pas assez complexe !",
                        'mode' => 'html5'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "L'adresse mail n'est pas assez longue !"
                    ])
                ]
            ])
            ->add('message', CKEditorType::class, [
                'label' => 'Votre message',
                'constraints' => [
                    new Length([
                        'min' => 40,
                        'max' => 1500,
                        'minMessage' => "Le message que vous souhaitez envoyé est trop court !",
                        'maxMessage' => "Le message que vous souhaitez envoyé est trop long !"
                    ])
                ]
            ])
            ->add('file', FileType::class, [
                'label' => "Ajoutez une capture d'écran",
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => "3M",
                        'maxSizeMessage' => "L'image fournie est trop lourde !",
                        'sizeNotDetectedMessage' => "Impossible de déterminer la taille de l'image !",
                        'detectCorrupted' => true
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => "Envoyer"])
        ;

        if ($this->env === 'prod') {
            $builder->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3 ([
                    'message' => 'Echec de la vérification captacha, il est possible que votre réseau nous envoie automatiquement des requêtes !',
                    'messageMissingValue' => 'La valeur du captcha est manquante !',
                ])
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
