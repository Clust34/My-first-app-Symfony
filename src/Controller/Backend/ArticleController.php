<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// creer la base d'une route pour afficher ensuite dans les méthodes
#[Route('/admin/articles', name: 'admin.articles')]
class ArticleController extends AbstractController
{
    // On instancie la class dans une variable pour l'utiliser sans la réécrire à chaque fois
    public function __construct(
        private ArticleRepository $articleRepo
    ) {
    }
    // Tu n'as plus besoins de tout réécrire à chaque fois
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Article/index.html.twig', [
            'articles' => $this->articleRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    // Cette classe écoute la requette afin de mettre en BDD le use est le httpFoundation
    public function create(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        // symfony voit si le formulaire est soumis ou pas
        $form->handleRequest($request);

        // Validation
        if ($form->isSubmitted() && $form->isValid()) {
            // On attache l'article au user
            $article->setUser($this->getUser());
            // On peut envoyer en BDD
            $this->articleRepo->save($article, true);

            $this->addFlash('success', 'Article créé avec succès');

            // Fait la redirection
            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form
        ]);
    }
}
