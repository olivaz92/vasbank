<?php


namespace App\Form;


use App\Entity\Formule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoixFormuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', EntityType::class,
                array('class' => 'App\Entity\Formule',
                    'label' => 'Formule',
                    'choice_label' => function($choixFormule){
                        return $choixFormule->getNom();
                    })
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formule::class,
        ]);
    }
}