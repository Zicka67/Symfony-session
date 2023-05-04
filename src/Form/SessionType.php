<?php

namespace App\Form;

use App\Entity\Former;
use App\Entity\Session;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title_section', TextType::class, [
            'label' => 'Titre de la session',
            'attr' => ['placeholder' => 'Entrez un titre de session']
        ])
        ->add('date_begin', DateTimeType::class, [
            'label' => 'Date de début',
            'widget' => 'single_text',
            'attr' => ['placeholder' => 'Entrez un date de début']
        ])
        ->add('date_end', DateTimeType::class, [
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'attr' => ['placeholder' => 'Entrez un date de fin']
        ])
        ->add('nb_places', IntegerType::class, [
            'label' => 'Nombre de places',
            'attr' => ['placeholder' => 'Entrez un nombre de places']
        ])
        ->add('formation', EntityType::class, [
            'label' => 'Formation',
            'class' => Formation::class,
            'choice_label' => 'title_formation',
            'placeholder' => 'Choisissez une formation',
        ])
        ->add('former', EntityType::class, [
            'label' => 'Formateur',
            'class' => Former::class,
            //Pour avoir le nom ET le prénom sur la même ligne
            'choice_label' => function ($former) {
                return $former->getFirstName() . ' ' . $former->getLastName();
            },
            'placeholder' => 'Choisissez un formateur',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
