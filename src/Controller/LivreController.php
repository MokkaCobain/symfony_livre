<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LivreController extends AbstractController
{
    // Liste des livres
    #[Route('/', name: 'app.livre')]
    public function index(LivresRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();
        return $this->render('livre/index.html.twig', compact('livres'));
    }

    // Ajout d'un livre
    #[Route('/ajout', name: 'ajout.livre', methods: ['GET', 'POST'])]
    public function create(Request $request, LivresRepository $livreRepository) : Response
    {
        $livre = new Livres();

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $livreRepository->add($livre, true);
            $this->addFlash('success', 'L\'ouvrage a bien été ajouté !');

            return $this->redirectToRoute('app.livre');
        }

        return $this->render('livre/ajout.html.twig', [
            'livre_form' => $form->createView(),
        ]);
    }

    // Modification d'un livre
    #[Route('/modifier/{id<\d+>}', name: 'modifier.livre', methods: ['GET', 'POST'])]
    public function edit(Livres $livre, Request $request, LivresRepository $livreRepository) : Response
    {
    
        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $livreRepository->add($livre, true);
            $this->addFlash('success', 'L\'ouvrage a bien été modifié !');

            return $this->redirectToRoute('app.livre');
        }

        return $this->render('livre/ajout.html.twig', [
            'livre_form' => $form->createView(),
        ]);
    }

    // Suppression d'un livre
    #[Route('/supprimer/{id<\d+>}', name: 'supprimer.livre', methods: ['POST'])]
    public function delete(Livres $livre, Request $request, LivresRepository $livreRepository) : Response
    {
        if ( $this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('_token')) )
        {
            $livreRepository->remove($livre, true);
            $this->addFlash('success', $livre->getTitle() . ' écrit par ' . $livre->getAuthor() . ' a bien été supprimé.');
        }

         // Redirection 
         return $this->redirectToRoute('app.livre');
    }
}
