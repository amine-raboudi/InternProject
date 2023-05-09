<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Form\AgentType;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AgentController extends AbstractController
{
   
    /**
     * @Route("/agence/{id}", name="agence_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $agence =$this->getDoctrine()->getRepository(Agent::class)->find($id);
  
        if (!$agence) {
  
            return $this->json('No agence found for id' . $id, 404);
        }
  
         $res[]=[
                'id'=>$agence->getId(),
                'email'=>$agence->getEmail(),
                'roles'=>$agence->getRoles(),
                'password'=>$agence->getPassword(),
                'status'=>$agence->getStatus()
                ];
          
        return $this->json($res);
    }
      /**
     * @Route("/agence/post", name="agence_post", methods={"POST"})
     */
    public function post(Request $request): Response
    {
        $agent=new Agent;
        $param=json_decode($request->getContent(),true );
        $agent->setEmail($param['email']);
        $agent->setRoles($param['roles']);
        $agent->setPassword($param['password']);
        $agent->setStatus($param['status']);

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($agent);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }

     /**
     * @Route("/agence/update/{id}", name="agence_update", methods={"PUT"})
     */
    public function update(Request $request,$id): Response
    {
        $agent=$this->getDoctrine()->getRepository(Agent::class)->find($id);
        $param=json_decode($request->getContent(),true );
        $agent->setEmail($param['email']);
        $agent->setRoles($param['roles']);
        $agent->setPassword($param['password']);
        $agent->setStatus($param['status']);

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($agent);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }
    /**
     * @Route("/agence/delete/{id}", name="agence_delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        $agent=$this->getDoctrine()->getRepository(Agent::class)->find($id);
        

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($agent);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }

     /**
     * @Route("/agenceAll", name="agence_list", methods={"GET"})
     */
    public function list(): Response
    {
        $agent=$this->getDoctrine()->getRepository(Agent::class)->findAll();
        foreach($agent  as  $d)
        {
            $res[]=[
                'id'=>$d->getId(),
                'email'=>$d->getEmail(),
                'roles'=>$d->getRoles(),
                'password'=>$d->getPassword(),
                'status'=>$d->getStatus()
                ];
        }

        
       
        return $this->json(
            $res
        );
    }


    
    }

