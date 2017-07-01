<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//parent::buildForm($builder, $options);
		$builder->add('bloub','text', array('mapped'=>false));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => $this->class,
				'intention'  => 'registration',
		));
	}

	public function getParent()
	{
		return 'fos_user_registration';
	}

	public function getName()
	{
		return 'shufler_user_registration';
	}
}