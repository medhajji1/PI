<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ nom est requis']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+\s?[a-zA-Z]*$/',
                        'message' => 'Le champ nom doit contenir uniquement des caractères alphabétiques avec un seul espace'
                    ])
                ]
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ email est requis']),
                    new Email(['message' => 'Le champ email doit contenir une adresse email valide'])
                ]
            ])
            ->add('sujet', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ sujet est requis']),
                ]
            ])
            ->add('message', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ message est requis']),
                ]
            ])
            ->add('numtel', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ numéro de téléphone est requis']),
                    new Regex([
                        'pattern' => '/^[2579][0-9]{7}$/',
                        'message' => 'Le champ numéro de téléphone doit commencer par 2, 5, 7 ou 9 et contenir 8 chiffres'
                    ])
                ]
            ])
            ->add('Ajouter', SubmitType::class, [
                'label' => 'Ajouter réclamation',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
