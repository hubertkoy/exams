<?php

namespace App\Form;

use App\Entity\Question;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $button_label = $options['button_label'];
        $builder
            ->add('question', TextType::class, [
                'label' => 'Pytanie',
            ])
            ->add('solution', CKEditorType::class, [
                'label' => 'Rozwiązanie',
                'config' => ['uiColor' => '#ffffff'],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Kategoria',
                'placeholder' => 'Wybierz kategorię',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $button_label,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'button_label' => 'Zapisz pytanie',
        ]);
    }
}
