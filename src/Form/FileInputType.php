<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Depot;
use App\Entity\SupportedFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileInputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file',FileType::class,['constraints' => [
                new File(
                    maxSize: '20m',
                    extensions: ['pdf'],
                    extensionsMessage: 'Seuls les fichiers PDF sont autorisés',
                )
            ]])
            ->add('name',TextType::class, ['required' => true, 'label' => "Nom"])
            ->add('fileType',EntityType::class,['label'=>'Type de Fichier',
                'class' => SupportedFileType::class,
                'choice_label' => 'displayName',
            ])
            ->add('submit',SubmitType::class, ['label'=>'Ajouter le dépôt'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}
