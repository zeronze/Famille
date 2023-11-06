<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(CalendarRepository $calendar): Response
    {
        $events = $calendar->findAll();
        $rdvs = [];

        foreach ($events as $event) {
            $user = $event->getUser(); // Récupérez l'objet utilisateur associé à l'événement
            $userPseudo = $user ? $user->getPseudo() : ''; // Obtenez le pseudo de l'utilisateur, ou une chaîne vide s'il n'y a pas d'utilisateur associé
        
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'userPseudo' => $userPseudo, // Ajoutez le pseudo de l'utilisateur
                'allDay' => $event->isAllDay(),
            ]; 
        }

        $data =json_encode($rdvs);




        return $this->render('main/index.html.twig',compact('data'));
    }
}
