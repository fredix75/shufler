<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SHUFLER\ShuflerBundle\Entity\Video;

class VideoType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lien', 'text')
            ->add('titre', 'text')
            ->add('auteur', 'text')
            ->add('chapo', 'textarea', array(
            'required' => false
        ))
            ->add('texte', 'textarea', array(
            'required' => false
        ))
            ->add('annee', 'text', array(
            'required' => false
        ))
            ->add('periode', 'choice', array(
            'required' => true,
            'placeholder' => 'Choose a period',
            'choices' => array_flip(Video::PERIOD_LIST),
            'choices_as_values' => true
        ))
            ->add('categorie', 'choice', array(
            'required' => true,
            'placeholder' => 'Choose a category',
            'choices' => array_flip(Video::CATEGORY_LIST),
            'choices_as_values' => true
        ))
            ->add('genre', 'choice', array(
            'required' => false,
            'placeholder' => 'Choose a style',
            'choices' => array_flip(Video::GENRE_LIST),
            'choices_as_values' => true
        ))
            ->add('priorite', 'choice', array(
            'required' => true,
            'choices' => Video::PRIORITY_LIST,
            'choices_as_values' => true
        ))
            ->add('moods', 'entity', array(
            'class' => 'SHUFLERShuflerBundle:Mood',
            'choice_label' => 'name',
            'multiple' => true,
            'required' => false
        ))
            ->add('published', 'checkbox', array(
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
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Video',
            'csrf_protection' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'shufler_shuflerbundle_video_edit';
    }
}
