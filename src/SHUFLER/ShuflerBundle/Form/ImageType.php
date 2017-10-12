<?php
namespace SHUFLER\ShuflerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder            
     * @param array $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('File', FileType::Class);
    }

    /**
     *
     * @param OptionsResolverInterface $resolver            
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SHUFLER\ShuflerBundle\Entity\Image'
        ));
    }

    /**
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shufler_shuflerbundle_image';
    }
}
