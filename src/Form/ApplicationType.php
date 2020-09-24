<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{
              /**
     * this function returns a label and placeholder for element text type
     *
     * @param [string] $label
     * @param [string] $placeholder
     * @param [array] $options
     * @return array containing label and attribute
     */
    protected function getConfiguration($label, $placeholder, $options = [])
    {
       return \array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ],$options);
    }
}


