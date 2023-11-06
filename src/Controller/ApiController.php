<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Calendar;
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface
use DateTime;

class ApiController extends AbstractController
{
    private EntityManagerInterface $entityManager; // Declare EntityManagerInterface

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('/api/{id}/edit', name: 'app_api_event_edit', methods: ['PUT'])]
    public function majEvent(?Calendar $calendar, Request $request): Response
    {
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor)
        ) {
            // Vérifiez si le calendrier existe et si l'utilisateur connecté est propriétaire du rendez-vous
            if (!$calendar || $calendar->getUser() !== $this->getUser()) {
                return new Response('Accès non autorisé', 403); // 403 Forbidden
            }

            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            if ($donnees->allDay) {
                $calendar->setEnd(new DateTime($donnees->start));
            } else {
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);

            // Use the injected EntityManager
            $this->entityManager->persist($calendar);
            $this->entityManager->flush();

            return new Response('Ok', 200); // 200 OK
        } else {
            return new Response('Données incomplètes', 400); // 400 Bad Request
        }
    }
}
