<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Owner;
use App\Entity\Region;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/rooms")
 */
class RoomsController extends AbstractController
{
    /**
     * @Route("/", name="rooms_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository): Response
    {
        $room=$roomRepository->findAll();
        $room['src']='hh';
        return $this->render('rooms/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/panier", name="show_panier", methods={"GET"})
     *
     */
    public function showPanier(): Response
    {
        $panier= $this->get('session')->get('panier');

    
    return $this->render('panier/panier.html.twig', [
        'panier' => $panier]);
    }
    /**
     * @Route("/new", name="rooms_new", methods={"GET","POST"})
     *@IsGranted("ROLE_PROPRIETAIRE","ROLE_COLLABORATEUR")
     */
    public function new(Request $request): Response
    {
        
        $room = new Room();
        //$room->setOwner($owner);
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //image
            $imagefile = $room->getImageFile();
            if($imagefile) {
               

            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('rooms_index', [], Response::HTTP_SEE_OTHER);
        }}

        return $this->render('rooms/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rooms_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('rooms/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rooms_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_PROPRIETAIRE","ROLE_COLLABORATEUR")
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rooms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rooms/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rooms_delete", methods={"POST"})
     * @IsGranted("ROLE_PROPRIETAIRE","ROLE_COLLABORATEUR")
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rooms_index', [], Response::HTTP_SEE_OTHER);
    }
/**
 * @Route("/addRoom/{id}", name="rooms_add", methods="GET|POST")
 * @IsGranted("ROLE_PROPRIETAIRE","ROLE_COLLABORATEUR")
 */
public function add(Request $request, Owner $owner): Response
{
    $room = new Room();
    //$owner = new owner();
    //$room->addRegion($region);
    $room->setOwner($owner);
    $form = $this->createForm(RoomType::class, $room);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($room);
        $entityManager->flush();

        return $this->redirectToRoute('rooms_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('rooms/new.html.twig', [
        'room' => $room,
        'form' => $form->createView(),
    ]);
}
/**
 * @Route("/marquer/{id}", name="marquer_reserve", methods="GET")
 * @IsGranted("ROLE_USER")
 */
public function marquerAsReserve(Room $room): Response
{
    $panier= $this->get('session')->get('panier');
    $id=$room->getId();
    $summary=$room->getSummary();
    $address=$room->getAddress();
    //$panier = $this->get('session')->get('panier');
            
            $s=$this->get('session')->get('panier');
            if($s == null)
                $s=array();
            $p['id']=$id;
            $p['summary']=$summary;
            $p['address']=$address;
            if (! in_array($p, $s))
                $panier[] =$p ;
            
        $this->get('session')->set('panier', $panier);
        return $this->redirectToRoute('rooms_index', 
        ['id' => $id]);
}

}
