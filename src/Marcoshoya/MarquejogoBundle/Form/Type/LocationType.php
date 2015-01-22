<?php

namespace Marcoshoya\MarquejogoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Location Form type
 *
 * Using ajax to create state/city fields
 *
 * @see
 *
 *  http://www.craftitonline.com/2011/08/symfony2-ajax-form-republish/
 *  http://aulatic.16mb.com/wordpress/2011/08/symfony2-dynamic-forms-an-event-driven-approach/
 *
 *  http://symfony.com/doc/current/cookbook/form/dynamic_form_modification.html
 */
class LocationType extends AbstractType
{

    private $entity;

    public function __construct($entity = null)
    {
        $this->entity = $entity;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $city = $this->entity->getCity();
        $state = null !== $city ? $city->getState() : null;

        // state fild
        $builder->add('state', 'entity', array(
            'class' => 'Marcoshoya\MarquejogoBundle\Entity\LocationState',
            'property' => 'name',
            'label' => 'Estado',
            'data' => $state,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                        ->where('s.country = :country')
                        ->orderBy('s.name', 'ASC')
                        ->setParameter('country', 31);
            },
        ));

        $formModifier = function (Form $form, $state) {
            $choices = array('' => '-- selecione --');
            if ($state instanceof \Marcoshoya\MarquejogoBundle\Entity\LocationState) {
                $form->add('city', 'entity', array(
                    'class' => 'MarcoshoyaMarquejogoBundle:LocationCity',
                    'property' => 'name',
                    'label' => 'Cidade',
                    'choices' => $state->getCity()
                ));
            } else {
                $form->add('city', 'choice', array('choices' => $choices));
            }
        };

        /**
         * This event will be trigger when form is load
         *  We define city collection based on state
         */
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($city) {
            $form = $event->getForm();
            $choices = array('' => '-- selecione --');
            if ($city instanceof \Marcoshoya\MarquejogoBundle\Entity\LocationCity) {
                $state = $city->getState();
                $form->add('city', 'entity', array(
                    'class' => 'Marcoshoya\MarquejogoBundle\Entity\LocationCity',
                    'label' => 'Cidade',
                    'data' => $city,
                    'query_builder' => function (EntityRepository $er) use ($state) {
                        return $er->createQueryBuilder('c')
                                ->where('c.state = :state')
                                ->orderBy('c.name', 'ASC')
                                ->setParameter('state', $state);
                    },
                ));
            } else {
                $form->add('city', 'choice', array('choices' => $choices));
            }
        }
        );

        /**
         * To validade new collection of city, post submit event is called
         *  we pass new collection using formModifier function
         */
        $builder->get('state')->addEventListener(
            FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $state = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $state);
        }
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * It defines the name of widget (type)
     */
    public function getName()
    {
        return 'location';
    }
}
