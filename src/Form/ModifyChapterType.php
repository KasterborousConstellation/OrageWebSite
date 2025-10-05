<?php

namespace App\Form;

use App\Entity\Chapitre;
use App\Entity\Cours;
use App\Entity\Depot;
use App\Entity\FicheExercice;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyChapterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomChapitre', TextType::class, ['label'=>'Nom du chapitre'])
            ->add('ordre',IntegerType::class, ['label'=>'Numéro de chapitre'])
            ->add('submit', SubmitType::class, ['label'=>'Modifier'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapitre::class,
        ]);
    }
}
