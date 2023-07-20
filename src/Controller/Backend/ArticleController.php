<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            $this->articleRepo->save($article);

            $this->addFlash('success', 'Article créé avec succès');

            // Fait la redirection
            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    // Article vas chercher dans sa class pour chercher l'id
    // Request c'est la requette HTTP
    public function edit(?Article $article, Request $request): Response
    {
        // instanceof est ce que c'est une instance de la class article
        // C'est pas un article
        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        // ArticleType::class récupère la class et le namespace
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleRepo->save($article);

            $this->addFlash('success', 'Article mis à jour avec succès');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Article/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request): RedirectResponse
    {
        // On vas chercher le bon article de part son id si il trouve pas tu met 0 donc pas de id à 0
        $article = $this->articleRepo->find($request->get('id', 0));

        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article non trouvé');

            // il n'y a aucun parametre donc tu met un tableau vide puis le code d'erreur
            return $this->redirectToRoute('admin.articles.index', [], 404);
        }

        // On vérifie le token 
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('token'))) {
            $this->articleRepo->remove($article);

            $this->addFlash('success', 'Article supprimé avec succès');

            return $this->redirectToRoute('admin.articles.index');
        }

        $this->addFlash('error', 'Token invalide');

        return $this->redirectToRoute('admin.articles.index');
    }
}
