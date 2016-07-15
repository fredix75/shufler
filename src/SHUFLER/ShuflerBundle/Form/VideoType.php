<?php

namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	   		->add('lien', 'textarea')
   			->add('titre', 'text')
   			->add('auteur','text')
   			->add('chapo', 'textarea', array('required' => false))
   			->add('texte', 'textarea', array('required' => false))
   			->add('annee', 'text', array('required' => false))
   			->add('periode', 'choice', array(
   					'required' => true,
   					'placeholder' => 'Choose a period',
    				'choices'  => array(
	        			'2016-2030' =>'2016-2030',
	        			'2001-2015' =>'2001-2015',
	        			'1986-2000' =>'1986-2000',
	   					'1971-1985' =>'1971-1985',
	   					'1956-1970' =>'1956-1970',
	   					'1940-1955' =>'1940-1955',
	    				'<1939'		=>'<1939'	
    			),
    			'choices_as_values' => true,
			))
   			->add('categorie', 'choice', array(
   					'required' => true,
   					'placeholder' => 'Choose a category',
    				'choices'  => array(
	    				'autre' => -1,
	        			'anim' =>1,
	        			'music' =>2,
	        			'étrange' =>3,
	   					'parodie' =>4,
						'Interview'=>5,
						'Tv'=>6,
						'Documentaire'=>7,
						'Cinéma'=>8,
						'TimeLapse/Nature'=>9	
    			),
    			'choices_as_values' => true,
			))
   			->add('genre', 'choice', array(
   					'required' => false,
   					'placeholder' => 'Choose a style',
	    			'choices'  => array(
	    				'autre' => -1,
						'Jazz/Blues'=>1,
						'Rock n\'Roll'=>2,
						'Rock/Pop'=>3,
						'Soul/Funk'=>4,
						'Reggae/Ragga/Dub'=>5,
						'Chanson'=>6,
						'Punk/Alternatif'=>7,
						'Hard-Rock'=>8,
						'Blues Rock'=>9,
						'Electro/DJs'=>10,
						'World'=>11,
						'Roots/Folk Rock'=>12,
						'Rock progressif/psyché'=>13,
						'Variétés / Pop'=>14,
						'Trip-Hop'=>15,
						'Afrobeat'=>16	
    			),
    			'choices_as_values' => true,
			))
   			->add('priorite', 'choice',array(
   					'required' => true,
   					'choices'=>array(1=>1,2=>2,3=>3,4=>4),
		   			'choices_as_values' => true,
   			))
   			->add('moods','entity', array(
  					'class'    => 'SHUFLERShuflerBundle:Mood',
  					'choice_label' => 'name',
					'multiple' => true,
   					'required' => false
   			))
   			->add('published', 'checkbox',array('required'=>false))
    		->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Video'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shufler_shuflerbundle_video_edit';
    }
}
