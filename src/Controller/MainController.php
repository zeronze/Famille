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
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'), // Utilisez des guillemets simples autour du format
                'end' => $event->getEnd()->format('Y-m-d H:i:s'), // Utilisez des guillemets simples autour du format
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'user' => $event->getUser(),
                'allDay' => $event->isAllDay(),

            ]; 
        }

        $data =json_encode($rdvs);




        return $this->render('main/index.html.twig',compact('data'));
    }
}
