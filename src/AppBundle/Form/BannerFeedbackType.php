<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BannerFeedbackType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', 'text', array('label' => 'Rating (1, 0, -1)'))
            ->add('message')
            ->add('save', 'submit', array('label' => 'Add Feedback'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\BannerFeedback'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_bannerfeedback';
    }
}
