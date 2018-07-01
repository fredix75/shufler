<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SHUFLER\ShuflerBundle\Entity\Flux;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FluxType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::Class)
            ->add('url', TextType::Class)
            ->add('type', ChoiceType::Class, array(
            'required' => true,
            'placeholder' => 'Choose a Type',
            'choices' => array_flip(Flux::FLUX_TYPE)
        ))
            ->add('mood', ChoiceType::Class, array(
            'required' => false,
            'placeholder' => 'Choose a Mood',
            'choices' => array_flip(Flux::RADIO_TYPE)
        ))
            ->add('logo', ImageType::Class, array(
            'required' => false
        ))
            ->add('channel', EntityType::Class, array(
            'class' => 'SHUFLERShuflerBundle:ChannelFlux',
            'choice_label' => 'name',
            'placeholder' => 'Choose a channel',
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
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Flux',
            'csrf_protection' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shufler_shuflerbundle_flux_edit';
    }
}
