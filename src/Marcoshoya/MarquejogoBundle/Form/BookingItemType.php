<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


/**
 * BookingItemType
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookingItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            1 => '01', 2 => '02', 3 => '03', 4 => '04', 5 => '05'
        );
        
        $builder
            ->add(
                $builder->create('date', 'hidden')
                ->addViewTransformer(new DateTimeToStringTransformer())
            )
            ->add('price', 'hidden')
            ->add('available', 'hidden')
            ->add('alocated', 'hidden')
            ->add('quantity', 'choice', array(
                'placeholder' => '00',
                'choices' => $choices,
                'mapped' => false
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $item = $event->getData();
            $form = $event->getForm();       
            $product = $item->getProviderProduct();
            if ($product instanceof \Marcoshoya\MarquejogoBundle\Entity\ProviderProduct) {
                $form->add('providerProduct', 'entity_hidden', array(
                    'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider',
                    'data' => $product,
                    'data_class' => null,
                    'label' => sprintf("%s|%s", ucwords($product->getName()), $product->getCapacity()),
                ));
            } else {
                $form->add('providerProduct');
            }
            
            $schedule = $item->getSchedule();
            if ($schedule instanceof \Marcoshoya\MarquejogoBundle\Entity\Schedule) {
                $form->add('schedule', 'entity_hidden', array(
                    'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider',
                    'data' => $schedule,
                    'data_class' => null,
                ));
            } else {
                $form->add('schedule');
            }
            
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\ScheduleItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_bookingitem';
    }
}
