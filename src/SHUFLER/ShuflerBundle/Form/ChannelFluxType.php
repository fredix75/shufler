<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SHUFLER\ShuflerBundle\Entity\ChannelFlux;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChannelFluxType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'required' => false
        ])
            ->add('image', FileType::Class, [
            'required' => false
        ])
            ->add('providerName', TextType::class, [
            'required' => false
        ])
        ->add('providerId', TextType::class, [
            'required' => false
        ])
        ->add('save', SubmitType::Class);;
    }

    /**
     *
     * @param OptionsResolverInterface $resolver            
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\ChannelFlux',
            'csrf_protection' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shufler_shuflerbundle_channelflux_edit';
    }
}

