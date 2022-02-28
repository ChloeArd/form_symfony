<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

    /**
     * List all available articles
     * @param ArticleRepository $repository
     * @return Response
     */
    #[Route('/', name: 'articles_list')]
    public function index(ArticleRepository $repository): Response {

        return $this->render('article/index.html.twig', [
            'articles' => $repository->findAll()
        ]);
    }

    /**
     * add a article
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/article/add', name: 'article_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response {

        $article = New Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager): Response {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $coppiesCount = (int)$form->get('copies')->getData();

            for ($i = 1; $i <= $coppiesCount; $i++) {
                $articleClone = new Article();
                $articleClone
                    ->setContent($article->getContent())
                    ->setTitle($article->getTitle() . " (copy {$i})")
                    ->setDateAdd(new \DateTime());

                $entityManager->persist($articleClone);
            }

            $published = $form->get('save')->isClicked();
            $article->setIsPublished($published);

            $entityManager->flush();
            $this->addFlash("success", "L'article a bien été mis à jour !");
            return $this->redirectToRoute("articles_list");
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/delete/{id}', name: 'article_delete')]
    public function delete(Article $article, Request $request, EntityManagerInterface $entityManager): Response {

        return $this->render('article/add.html.twig', []);
    }
}
