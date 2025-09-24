<?php

namespace App\Form;

use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifiedAnnounceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slug', TextType::class, ['label'=>'Description','attr'=>['placeholder'=>'Entrez une courte description de l\'annonce']] )
            ->add('title', TextType::class,[
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Entrez le titre de l\'annonce'],
            ] )
            ->add('content',TextAreaType::class,
        ['label' => 'Contenu', 'attr' => ['placeholder' => 'Entrez le contenu de l\'annonce', 'rows' => 10, 'cols' => 50, 'style' => 'resize:none;']])
            ->add('expirateAt',\Symfony\Component\Form\Extension\Core\Type\DateType::class,  [
                'label' => 'Date D\'expiration',

            ])
            ->add('submit',SubmitType::class,['label' => 'Modifier'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
    public function createForm(FormBuilderInterface $builder, array $options): void{

    }
}
