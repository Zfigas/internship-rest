<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class HubType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder->add('search', 'text')->add('nrOfResult', 'text')->add('sort', 'text' )->add('page', 'text')->add('submit', 'submit');
    }
    public function getHub(){
        return 'hub';
    }
}