<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\CategoryOffer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="app_offer_index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository): JsonResponse
    {
        $offers = $this->getDoctrine()->getRepository(Offer::class)->findAll();

        $data = [];
        foreach ($offers as $offer) {
            $data[] = [
                'id' => $offer->getId(),
                'Price' => $offer->getPrice(),
                'DateStart' => $offer->getDateStart(),
                'DateEnd' => $offer->getDateEnd(),
                'Active'=>$offer->isIsActive(),
                'Category' => $offer->getCategory()->getType(),

                
            ];
        }
        
        
    
        return new JsonResponse($data);
    }
    
     

    /**
     * @Route("/new", name="app_offer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OfferRepository $offerRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

    $offer = new Offer();
    $offer->setPrice($data['Price']);
    $offer->setIsActive($data['Active']);


    
    // Convert DateStart string to DateTime object
    $dateStart = new \DateTime($data['DateStart']);
    $offer->setDateStart($dateStart);

    // Convert DateEnd string to DateTime object
    $dateEnd = new \DateTime($data['DateEnd']);
    $offer->setDateEnd($dateEnd);
    $categoryId = $data['Category'];
    $category = $this->getDoctrine()->getRepository(CategoryOffer::class)->find($categoryId);
    $offer->setCategory($category);
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($offer);
    $entityManager->flush();


       return new JsonResponse('Offer Added .');
    }

    /**
     * @Route("/{id}", name="app_offer_show", methods={"GET"})
     */
    public function show(Offer $offer,$id): JsonResponse
    {
        $offer =$this->getDoctrine()->getRepository(Offer::class)->find($id);
  
        if (!$offer) {
  
            return $this->json('No agence found for id' . $id, 404);
        }
  
         $res[]=[
                'id'=>$offer->getId(),
                'Price'=>$offer->getPrice(),
                'DateStart'=>$offer->getDateStart(),
                'DateEnd'=>$offer->getDateEnd(),
                'Category'=>$offer->getCategory()->getId(),
                
                ];
          
                return new JsonResponse($res);  
              }
    

   /**
 * @Route("/update/{id}", name="offer_edit", methods={"PUT"})
 */
public function edit(Request $request, Offer $offer): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Update the properties of the offer entity
    $offer->setPrice($data['Price']);
    $offer->setDateStart(new \DateTime($data['DateStart']));
    $offer->setDateEnd(new \DateTime($data['DateEnd']));

    // Get the category ID from the request data
    $categoryId = $data['Category'];

    // Fetch the category entity based on the ID
    $category = $this->getDoctrine()->getRepository(CategoryOffer::class)->find($categoryId);

    // Set the category in the offer entity
    $offer->setCategory($category);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();


    return new JsonResponse('Offer Updated');
}


   /**
 * @Route("/delete/{id}", name="offer_delete", methods={"DELETE"})
 */
public function delete(Offer $offer): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($offer);
    $entityManager->flush();

    return new JsonResponse('Offer deleted');
}
}
