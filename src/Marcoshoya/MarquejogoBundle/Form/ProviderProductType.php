<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\IntegerToLocalizedStringTransformer;

class ProviderProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', 'choice', array(
                'choices' => array(
                    'open' => 'Aberto', 
                    'close' => 'Fechado'
                ),
                'placeholder' => 'Escolha a categoria',
            ))
            ->add('type', 'choice', array(
                'choices' => array(
                    'soccer' => 'Futebol de salão', 
                    'swiss' => 'Futebol suiço',
                    'voley' => 'Voleibol'
                ),
                'placeholder' => 'Escolha o tipo',
            ))
            ->add('isActive')
            ->add('provider', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider'
            ))
            ->add('capacity', 'integer', array(
                'rounding_mode' => IntegerToLocalizedStringTransformer::ROUND_CEILING
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\ProviderProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_providerproduct';
    }
}
