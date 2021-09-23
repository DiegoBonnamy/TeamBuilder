<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TeamRepository;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Team;
use App\Entity\Person;
use App\Form\TeamType;
use App\Form\PersonType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TeamRepository $teamRepository, PersonRepository $personRepository, Request $request): Response
    {
        // Form Team
        $team = new Team();
        $form_team = $this->createForm(TeamType::class, $team);
        $form_team->handleRequest($request);

        if ($form_team->isSubmitted() && $form_team->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        // Form Person
        $person = new Person();
        $form_person = $this->createForm(PersonType::class, $person);
        $form_person->handleRequest($request);

        if ($form_person->isSubmitted() && $form_person->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('home/index.html.twig', [
            'teams' => $teamRepository->findAll(),
            'form_team' => $form_team->createView(),
            'persons' => $personRepository->findAll(),
            'form_person' => $form_person->createView(),
        ]);
    }
    
    // ---------- TEAM ---------- //

    /**
     * @Route("/remove/team/{id}", name="remove_team")
     */
    public function remove_team(Team $team): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($team);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    // ---------- PERSON ---------- //

    /**
     * @Route("/remove/person/{id}", name="remove_person")
     */
    public function remove_person(Person $person): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($person);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

}
