<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TeamType extends AbstractType
{
    protected $em;
    
    public function __construct($em = null)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('owner', 'entity_hidden', array(
                'class' => 'Marcoshoya\MarquejogoBundle\Entity\Customer',
            ))
        ;
        
        if (null !== $this->em) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $team = $event->getData();
                $form = $event->getForm();
                $customer = $team->getOwner();
                if ($customer->getId()) {
                    $choices = $this->getChoices($customer);
                    $form->add('name', 'choice', array(
                        'choices' => $choices
                        ));
                } else {
                    $form->add('name');
                }
            });
        } else {
            $builder->add('name');
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\Team',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_team';
    }
    
    /**
     * get choices
     * 
     * @param type $customer
     * @return array
     */
    private function getChoices($customer)
    {
        $teams = $this->em->getRepository('MarcoshoyaMarquejogoBundle:Team')
            ->findBy(array(
                'owner' => $customer
            ));
        
        $choices = array();
        foreach ($teams as $team) {
            $choices[$team->getName()] = $team->getName();
        }
        
        return $choices;
    }

}
