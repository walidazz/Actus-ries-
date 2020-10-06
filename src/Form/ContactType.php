<?php

namespace App\Form;

use App\Entity\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Beelab\Recaptcha2Bundle\Form\Type\RecaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class,  ['label' => 'Nom'])
            ->add('email', EmailType::class,  ['label' => 'Email'])
            ->add('motif', TextType::class,  ['label' => 'Sujet'])
            ->add('message', TextareaType::class, ['label' => 'Message'])
            ->add('captcha', RecaptchaType::class, [
                'constraints' => new Recaptcha2(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
