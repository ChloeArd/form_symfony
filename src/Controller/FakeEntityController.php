<?php

namespace App\Controller;

use App\Entity\FakeEntity;
use App\Form\FakeEntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FakeEntityController extends AbstractController {

    #[Route('/fake/add', name: 'fake_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response {

        $fake = New FakeEntity();
        $form = $this->createForm(FakeEntityType::class, $fake);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fake);
            $entityManager->flush();
        }

        return $this->render('fake_entity/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
