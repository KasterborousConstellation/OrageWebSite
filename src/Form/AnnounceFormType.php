<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnounceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Entrez le titre de l\'annonce'],
            ])
            ->add('slug',TextType::class, ['label'=>'Description','attr'=>['placeholder'=>'Entrez une courte description de l\'annonce']])

            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Entrez le contenu de l\'annonce', 'rows' => 10, 'cols' => 50, 'style' => 'resize:none;'],
            ])
            ->add("submit",SubmitType::class,['label'=>'Créer l\'annonce'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
