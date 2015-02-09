<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerType extends AbstractType
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
            ->add('cpf')
            ->add('gender', 'choice', array(
                'placeholder' => 'Escolha seu gênero',
                'choices' => array('male' => 'Masculino', 'female' => 'Feminino')
            ))
            ->add('position', 'choice', array(
                'placeholder' => 'Escolha sua posição',
                'choices' => $this->getPositionChoice()
            ))
            ->add('birthday', 'birthday', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('phone')
            ->add('address')
            ->add('number')
            ->add('complement')
            ->add('neighborhood')
            ->add('city')
            ->add('state')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\Customer',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_customer';
    }

    private function getPositionChoice()
    {
        return array(
            'goalkeeper' => 'Goleiro',
            'defender' => 'Defensor',
            'middle' => 'Meio',
            'attacker' => 'Atacante'
        );
    }

}
