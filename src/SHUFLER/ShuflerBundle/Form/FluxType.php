<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SHUFLER\ShuflerBundle\Entity\Flux;

class FluxType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('url', 'text')
            ->add('type', 'choice', array(
            'required' => true,
            'placeholder' => 'Choose a Type',
            'choices' => array_flip(Flux::FLUX_TYPE),
            'choices_as_values' => true
        ))
            ->add('mood', 'choice', array(
            'required' => false,
            'placeholder' => 'Choose a Mood',
            'choices' => array_flip(Flux::RADIO_TYPE),
            'choices_as_values' => true
        ))
            ->add('logo', new ImageType(), array(
            'required' => false
        ))
            ->add('save', 'submit');
    }

    /**
     *
     * @param OptionsResolverInterface $resolver            
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Flux',
            'csrf_protection' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'shufler_shuflerbundle_flux_edit';
    }
}
