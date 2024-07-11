<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Regex;

class XSSClientType extends AbstractType
{
    public function __construct(private readonly UrlGeneratorInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->router->generate('app_webattacks_xss'));
        $builder->add('name', TextType::class, ['constraints' => [new Regex('/^[\w\s]+$/')]]) # валідувати на правильний формат (недопущення збереження скриптів)
            ->add('age', IntegerType::class);
    }
}