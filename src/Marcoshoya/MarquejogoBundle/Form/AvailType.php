<?php

namespace Marcoshoya\MarquejogoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AvailType
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class AvailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $min = date('Y, m, d');
        $max = date('Y, m, d', strtotime("+4 months"));
        
        $builder
            ->add('provider', 'hidden')
            ->add('date', 'genemu_jquerydate', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'culture' => 'pt-BR',
                'years' => array($min, $max),
                'constraints' => array(
                    new Assert\NotBlank(array('message' => "Campo obrigatório")),
                    new Assert\Date(array('message' => "valor inválido"))
                ),
            ))
            ->add('hour', 'choice', array(
                'choices' => $this->getHourOptions(),
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marcoshoya_marquejogobundle_avail';
    }

    /**
     * Get choices
     *
     * @return array()
     */
    private function getHourOptions()
    {
        $initial = new \DateTime(date("Y-m-d 00:00:00"));
        $final = clone $initial;
        $final->modify('+1 day');
        $initial->modify('+6 hours');

        $options = array();
        for ($time = $initial->getTimestamp(); $time < $final->getTimestamp(); $time = strtotime('+1 hour', $time)) {
            $date = new \DateTime(date('Y-m-d H:i:s', $time));
            $options[$date->format('H')] = $date->format('H:i');
        }

        return $options;
    }
}
