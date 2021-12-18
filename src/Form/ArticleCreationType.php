<?php

namespace App\Form;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('contenu',TextareaType::class)
            ->add('isPremium')
            ->add('image',FileType::class,[
                'label'=>'Ajouter une illustration à votre publication',
                'required'=>false,
                'data_class' => null

            ])
            ->add('submit',SubmitType::class,[
                'label'=>"Créer mon article",
                'attr'=>[
                    'class'=>'btn-primary btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
