<?php

namespace App\Form;

use App\Entity\Restorer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestorerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('restaurantName',TextType::class)
            ->add('email',EmailType::class)
            ->add('password', PasswordType::class)
 /*            ->add('image') */
            ->add('coverFile',FileType::class,[
                'label'=>"Choisissez une image pour votre restaurant",
                'mapped'=>false
            ])
            ->add('address', AddressType::class)
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restorer::class,
        ]);
    }
}
