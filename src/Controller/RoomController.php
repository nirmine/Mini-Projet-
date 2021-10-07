<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Room;
/**
 * Controleur Room
 * @Route("/room")
 */
class roomController extends AbstractController
{
    /**
     * @Route("/", name="room")
     */
    public function index(): Response
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }

       /**
     * @Route("/rooms" ,methods="GET",name="rooms")
     */
    public function _room_show()
    {
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository(Room::class)->findAll();
       
        return $this->render('room/clientSide.html.twig', [
            'rooms' => $rooms,
        ]);
    }

        /**
     * @Route("/{id}" ,methods="GET", name="room_show", requirements={ "id": "\d+"}, methods="GET"))
     */
    public function room_show(Room $room)
    {
       
        return $this->render('room/roomDetails.html.twig', [
            'room' => $room,
        ]);
    }
}
