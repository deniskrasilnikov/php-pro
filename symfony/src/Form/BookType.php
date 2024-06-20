<?php

declare(strict_types=1);

namespace App\Form;

use App\Validator\Isbn10;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Literato\Entity\Author;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Novel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Book name',
                    'pattern' => false // подавити валідацію в браузері
                ]
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.id')
                        ->setMaxResults(15); // лімітую список авторів 15-ма за для прикладу
                },
                'choice_label' => 'fullName',
                'placeholder' => 'Choose book author'
            ])
            ->add('isbn10', null, [
                'label' => 'ISBN-10',
                'constraints' => [
                    new Isbn10(),
                ]
            ])
            ->add('genres', EnumType::class, [
                'label' => 'Genres',
                'class' => Genre::class,
                'multiple' => true,
                'expanded' => true
            ])->add('text', TextareaType::class, [
                'label' => 'Text',
                'attr' => [
                    'cols' => 60,
                    'rows' => 7,
                    'placeholder' => 'Enter book text here ...'
                ],
            ]);

        if ($builder->getData() instanceof Novel) {
            $builder->add('synopsis', null, [
                'label' => 'Synopsis',
            ]);
        }
    }
}