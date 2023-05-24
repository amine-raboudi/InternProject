<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservation;
use App\Entity\Offer;
use App\Entity\Client;
use App\Entity\Agent;

use App\Repository\ReservationRepository;


/**
     * @Route("/reservation", name="app_reservation")
     */
class ReservationController extends AbstractController
{
    
   
    /**
     * @Route("/", name="app_reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): JsonResponse
    {
        $Reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();

        $data = [];
        foreach ($Reservations as $Reservation) {
            $data[] = [
                'id' => $Reservation->getId(),
                'Offer' => $Reservation->getOffer()->getId(),
                'Client' => $Reservation->getClient()->getEmail(),
                'Agent' => $Reservation->getAgent()->getEmail(),

                
            ];
        }
        
        
    
        return new JsonResponse($data);
    }
    
     

    /**
     * @Route("/new", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository): JsonResponse
    {
    $data = json_decode($request->getContent(), true);
    $Reservation = new Reservation();
    $OfferId = $data['Offer'];
    $ClientId=$data['Client'];
    $AgentId=$data['Agent'];

    $Offer = $this->getDoctrine()->getRepository(Offer::class)->find($OfferId);
    $Client = $this->getDoctrine()->getRepository(Client::class)->find($ClientId);
    $Agent = $this->getDoctrine()->getRepository(Agent::class)->find($AgentId);

    $Reservation->setOffer($Offer);
    $Reservation->setClient($Client);
    $Reservation->setAgent($Agent);

    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($Reservation);
    $entityManager->flush();


       return new JsonResponse('Reservation Added .');
    }

    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"})
     */
    public function show(Reservation $Reservation,$id): JsonResponse
    {
        $Reservation =$this->getDoctrine()->getRepository(Reservation::class)->find($id);
  
        if (!$Reservation) {
  
            return $this->json('No Reservation found for id ' . $id, 404);
        }
  
         $res[]=[
            'id' => $Reservation->getId(),
            'Offer' => $Reservation->getOffer()->getId(),
            'Client' => $Reservation->getClient()->getEmail(),
            'Agent' => $Reservation->getAgent()->getEmail(),

                
                ];
          
                return new JsonResponse($res);  
              }
    

   /**
 * @Route("/update/{id}", name="reservation_edit", methods={"PUT"})
 */
public function edit(Request $request, Reservation $Reservation): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    
    // Update the properties of the offer entity
    $OfferId = $data['Offer'];
    $ClientMail=$data['Client'];
    $AgentMail=$data['Agent'];
    $Client = $this->getDoctrine()->getRepository(Client::class)->findOneBySomeField($ClientMail);
    $Agent = $this->getDoctrine()->getRepository(Agent::class)->findOneByMail($AgentMail);
    $Offer = $this->getDoctrine()->getRepository(Offer::class)->find($OfferId);

    $Reservation->setOffer($Offer);
    $Reservation->setClient($Client);
    $Reservation->setAgent($Agent);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();


    return new JsonResponse('Reservation Updated');
}


   /**
 * @Route("/delete/{id}", name="reservation_delete", methods={"DELETE"})
 */
public function delete(Reservation $Reservation): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($Reservation);
    $entityManager->flush();

    return new JsonResponse('Reservation deleted');
}
}
