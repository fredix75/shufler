<?php

namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FluxType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	   		->add('name', 'text')
   			->add('url', 'text')
   			->add('type', 'choice', array(
   					'required' => true,
   					'placeholder' => 'Choose a Type',
   					'choices'=>array('rss'=>1,'podcast'=>2, 'radio'=>3),
		   			'choices_as_values' => true,
   			))
   			->add('mood', 'choice', array(
   					'required' => false,
   					'placeholder' => 'Choose a Mood',
   					'choices'=>array('généraliste'=>1,'rock'=>2, 'jazz'=>3, 'groove'=>4, 'reggae'=>5, 'disco'=>6, 'funk'=>7, 'classique'=>'8', 'electro'=>9 , 'bossa nova'=>10, 'autre'=>99),
   					'choices_as_values' => true,
   			))
   			->add('logo',  new ImageType(), array(
            		'required'=>false
            ))
    		->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Flux'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shufler_shuflerbundle_flux_edit';
    }
}
