<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                // Pour traduire le champ titre tu fais une clées pour allez le prendre dans translate
                'label' => 'article.title.label',
                'required' => true,
                // Il y as un bundle pour cette attribut cf la doc sur texttype field
                'sanitize_html' => true,
                // attr c'est pour les attribut html c'est pour ca que tu dois le mettre dedans
                'attr'  => [
                    'placeholder' => 'article.title.placeholder'
                ]
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'article.metaTitle.label',
                'required' => true,
                'sanitize_html' => true,
                'attr'  => [
                    'placeholder' => 'article.metaTitle.placeholder'
                ]
            ])
            ->add('metaDescription', TextType::class, [
                'label' => 'article.metaDesc.label',
                'required' => true,
                'sanitize_html' => true,
                'attr'  => [
                    'placeholder' => 'article.metaDesc.placeholder'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'article.content.label',
                'required' => true,
                'sanitize_html' => true,
                'attr'  => [
                    'placeholder' => 'article.content.placeholder',
                    'rows' => 5,
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'article.enable.label',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Ne pas oublier l'option translation... pour que la clé trouve son chemin
        $resolver->setDefaults([
            'data_class' => Article::class,
            // Le form vien du fichier dans translations form.en.yaml
            'translation_domain' => 'form'
        ]);
    }
}
