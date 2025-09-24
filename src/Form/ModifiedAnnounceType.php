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
            ->add('slug', TextType::class, ['label' => 'Description', 'value' =>$options['slug']])
            ->add('title', TextType::class, ['label' => 'Title', 'value' =>$options['title']])
            ->add('content',TextAreaType::class, ['label' => 'Contenu' , 'value' =>$options['content']])
            ->add('expirationDate',DateType::class, ['label' => 'Date d\'expiration', 'value' =>$options['date']])
            ->add('submit',SubmitType::class,['label' => 'Modifier'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
