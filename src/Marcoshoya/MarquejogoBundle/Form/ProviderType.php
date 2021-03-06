<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProviderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('name')
            ->add('description')
            ->add('cnpj')
            ->add('phone')
            ->add('address')
            ->add('number')
            ->add('complement')
            ->add('neighborhood')
            ->add('city')
            ->add('isActive')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_provider';
    }
}
