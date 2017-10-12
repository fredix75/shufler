<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SHUFLER\ShuflerBundle\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VideoType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lien', TextType::class)
            ->add('titre', TextType::class)
            ->add('auteur', TextType::class)
            ->add('chapo', TextareaType::class, array(
            'required' => false
        ))
            ->add('texte', TextareaType::class, array(
            'required' => false
        ))
            ->add('annee', TextType::class, array(
            'required' => false
        ))
            ->add('periode', ChoiceType::class, array(
            'required' => true,
            'placeholder' => 'Choose a period',
            'choices' => array_flip(Video::PERIOD_LIST),
            'choices_as_values' => true
        ))
            ->add('categorie', ChoiceType::class, array(
            'required' => true,
            'placeholder' => 'Choose a category',
            'choices' => array_flip(Video::CATEGORY_LIST),
            'choices_as_values' => true
        ))
            ->add('genre', ChoiceType::class, array(
            'required' => false,
            'placeholder' => 'Choose a style',
            'choices' => array_flip(Video::GENRE_LIST),
            'choices_as_values' => true
        ))
            ->add('priorite', ChoiceType::class, array(
            'required' => true,
            'choices' => Video::PRIORITY_LIST,
            'choices_as_values' => true
        ))
            ->add('moods', EntityType::Class, array(
            'class' => 'SHUFLERShuflerBundle:Mood',
            'choice_label' => 'name',
            'multiple' => true,
            'required' => false
        ))
            ->add('published', CheckboxType::Class, array(
            'required' => false
        ))
        ->add('save', SubmitType::Class);
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
    public function getBlockPrefix()
    {
        return 'shufler_shuflerbundle_video_edit';
    }
}
