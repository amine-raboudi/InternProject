<?php

namespace App\Controller;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class ClientSessionController extends AbstractController
{
    /**
     * @Route("/client/{id}", name="client_show", methods={"GET"})
     */
    public function show( int $id): Response
    {
        $client =$this->getDoctrine()->getRepository(Client::class)->find($id);
  
        if (!$client) {
  
            return $this->json('No client found for id' . $id, 404);
        }
  
         $res[]=[
                'id'=>$client->getId(),
                'email'=>$client->getEmail(),
                'roles'=>$client->getRoles(),
                'password'=>$client->getPassword(),
                'is_verified'=>$client->isIsVerified()
                ];
          
        return $this->json($res);
    }
  

      /**
     * @Route("/client/post", name="client_post", methods={"POST"})
     */
    public function post(Request $request): Response
    {
        $client=new Client;
        $param=json_decode($request->getContent(),true );
        $client->setEmail($param['email']);
        $client->setRoles($param['roles']);
        $client->setPassword($param['password']);
        $client->setIsVerified($param['is_verified']);

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }

     /**
     * @Route("/client/update/{id}", name="client_update", methods={"PUT"})
     */
    public function update(Request $request,$id): Response
    {
        $client=$this->getDoctrine()->getRepository(Client::class)->find($id);
        $param=json_decode($request->getContent(),true );
        $client->setEmail($param['email']);
        $client->setRoles($param['roles']);
        $client->setPassword($param['password']);
        $client->setIsVerified($param['is_verified']);

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }
    
    /**
     * @Route("/client/delete/{id}", name="client_delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        $client=$this->getDoctrine()->getRepository(Client::class)->find($id);
        

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }

     /**
     * @Route("/clientAll", name="client_list", methods={"GET"})
     */
    public function list(): Response
    {
        $client=$this->getDoctrine()->getRepository(Client::class)->findAll();
        foreach($client  as  $d)
        {
            $res[]=[
                'id'=>$d->getId(),
                'email'=>$d->getEmail(),
                'roles'=>$d->getRoles(),
                'password'=>$d->getPassword(),
                'is_verified'=>$d->isIsVerified()
                ];
        }

        
       
        return $this->json($res);
    }


}
