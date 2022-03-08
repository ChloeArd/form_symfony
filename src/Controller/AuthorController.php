<?php

namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/add', name: 'author_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag): Response {

        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['bioFile']->getData();
            $ext = $file->getExtension();
            if (!$ext) {
               $this->redirectToRoute("/");
            }
            else {
                $nameFile = uniqid() . "." . $ext;
                move_uploaded_file($file, "./img/" . $nameFile);
                $entityManager->persist($author);
                $entityManager->flush();
                $this->addFlash("success", "L'auteur a été créé avec succès !");
            }

        }
        return $this->render('author/index.html.twig', ['form' => $form->createView()]);
    }
}
