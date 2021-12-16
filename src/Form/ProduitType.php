<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['add']==true): // si on est en ajout c'est ce formulaire qui est utilisé     
            $builder
                ->add('title',TextType::class,[
                    'required'=>false, //désactivation JS
                    'label'=>false, //pas de label on le mettra nous mm
                    'attr'=>[ //attr ce sont les attribut des inputs (name,placeholder, id, class...) on doit faire un tableau multidimansionnel
                        "placeholder"=> "Saisir le nom du produit"
                    ],
                ])
                ->add('price', NumberType::class,[
                    'required'=>false,
                    'label'=>false,
                    'attr'=>[
                        "placeholder"=>"Saisir le prix"
                    ]
                ])
                ->add('description', TextareaType::class,[
                    'required'=>false,
                    'label'=>false
                    //pas de placehorder ds des textearea
                ])
                ->add('picture', FileType::class,[
                    'required'=> false,
                    'label'=>false,
                    'constraints'=>[
                        new File([ //appel de new File (celui de validator constraints)
                            'mimeTypes'=>[ //controle du format avec un , (pour s'occuper de la taille ce serait maxSize)
                                'image/png', //tableau de format autorisé
                                'image/jpg',
                                'image/jpeg',
                            ],
                            'mimeTypesMessage'=>"Les extensions autorisée sont: PNG, JPG, JPEG"
                        ])
                    ]
                ])
                ->add('categorie', EntityType::class,[
                    'class'=>Categorie::class,
                    "choice_label"=>"nom"
                ])
                ->add('Valider', SubmitType::class);

        elseif($options['update'] == true):
                $builder
                ->add('title', TextType::class, [
                    'required' => false, //désactivation JS
                    'label' => false, //pas de label on le mettra nous mm
                    'attr' => [ //attr ce sont les attribut des inputs (name,placeholder, id, class...) on doit faire un tableau multidimansionnel
                        "placeholder" => "Saisir le nom du produit"
                    ],
                ])
                ->add('price', NumberType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        "placeholder" => "Saisir le prix"
                    ]
                ])
                ->add('description', TextareaType::class, [
                    'required' => false,
                    'label' => false
                    //pas de placehorder ds des textearea
                ])
                ->add('updatePicture', FileType::class, [
                    'required' => false,
                    'label' => false,
                    'constraints' => [
                        new File([ //appel de new File (celui de validator constraints)
                            'mimeTypes' => [ //controle du format avec un , (pour s'occuper de la taille ce serait maxSize)
                                'image/png', //tableau de format autorisé
                                'image/jpg',
                                'image/jpeg',
                            ],
                            'mimeTypesMessage' => "Les extensions autorisées sont : PNG, JPG, JPEG"
                        ])
                    ]
                ])
                ->add('categorie', EntityType::class, [
                    'class' => Categorie::class,
                    "choice_label" => "nom"
                ])
                ->add('Valider', SubmitType::class);
                
        endif;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'add'=>false,
            'update'=>false
        ]);
    }
}
