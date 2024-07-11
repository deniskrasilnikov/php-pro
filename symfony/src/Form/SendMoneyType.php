<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendMoneyType extends AbstractType
{
    public function __construct(private readonly UrlGeneratorInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->router->generate('app_webattacks_sendmoney_form'));
        $builder
            ->add('amount', IntegerType::class)
            ->add('address', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'csrf_protection' => false
            ]);
    }
}