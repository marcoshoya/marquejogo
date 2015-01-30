<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ScheduleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $schedule = $event->getData();
            $form = $event->getForm();
            $provider = $schedule->getProvider();
            if ($provider instanceof Provider) {
                $form->add('provider', 'entity_hidden', array(
                    'class' => 'Marcoshoya\MarquejogoBundle\Entity\Provider',
                    'data' => $provider,
                    'data_class' => null,
                ));
            } else {
                $form->add('provider');
            }
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marcoshoya\MarquejogoBundle\Entity\Schedule',
            'constraints' => array(new Callback(array('methods' => array(array($this, 'checkOptions'))))),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_schedule';
    }

    public function checkOptions($data, ExecutionContextInterface $context)
    {
        $scheduleItem = $data->getScheduleItem();

        $valid = false;
        foreach ($scheduleItem as $item) {
            if (null !== $item->getAlocated()) {
                $valid = true;
            }
        }

        if (false === $valid) {
            $context->addViolation("Selecione ao menos uma quadra para reservar");
        }
    }

}
