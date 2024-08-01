<?php

declare(strict_types=1);

namespace App\Form;

use App\Module\Literato\Entity\BookTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // приклад ДИНАМІЧНОГО стоврення елементів форми, коли якісь з опцій залежать від даних форми
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event): void {
            /** @var BookTranslation $bookTranslation */
            $bookTranslation = $event->getData();
            $form = $event->getForm();
            $form->add('name', null, [
                // значення лейблу залежить від локалі об'єкту
                'label' => 'Name [%locale%]',
                'label_translation_parameters' => [
                    '%locale%' => $bookTranslation->getLocale()
                ],
                'attr' => [
                    'pattern' => false // подавити валідацію в браузері
                ]
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => BookTranslation::class,
            'label' => false,
        ]);
    }
}