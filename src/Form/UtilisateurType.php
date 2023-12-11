<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $mimeTypes = new MimeTypes();
        $imageMimeTypes = $mimeTypes->getMimeTypes('png,jpeg,jpg,gif');


        $builder
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('motdepasse',PasswordType::class)
            ->add('numerotelephone')
       
        
            ->add('typeutilisateur',ChoiceType::class,[
                    'choices'=>[
                        'client' =>'Client',
                        'admin' =>'Admin',
                    ],
                    'expanded' => true,
                    'multiple' =>false,
        
                ])
 
           
                ->add('image', FileType::class, [
                    'label' => 'Brochure (PDF file)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024M',
                            'mimeTypes' =>$imageMimeTypes
                            ,
                            'mimeTypesMessage' => 'Please upload a valid image  document',
                        ])
                    ]]);
 

            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,


        ]);
    }
}
