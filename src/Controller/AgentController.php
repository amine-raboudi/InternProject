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
                'status'=>$agence->getStatus(),
                'adress'=>$agence->getAdress(),
                'phoneNumber'=>$agence->getPhoneNumber(),
                'country'=>$agence->getCountry(),
                'name'=>$agence->getName(),
                'logo'=>$agence->getLogo(),
                'city'=>$agence->getCity(),
                'roles'=>$agence->getRoles(),
                'confirmPaswword'=>$agence->getConfirmPaswword(),
                'status'=>$agence->getStatus(),


                ];
          
        return $this->json($res);
    }
      /**
     * @Route("/agence/post", name="agence_post", methods={"POST"})
     */
    public function post(Request $request): Response
    {
        $Agent=new Agent;
        $data=json_decode($request->getContent(),true );
        $Agent->setEmail( $data['email']);
        $Agent->setRoles(["ROLE_AGENT"]);
        $Agent->setName($data['name']);
        $Agent->setAdress($data['address']);
        $Agent->setPhoneNumber( $data['phoneNumber']);
        $Agent->setCountry($data['country']);
        $Agent->setLogo($data['logo']);
        $Agent->setPassword( $data['password']);
        $Agent->setConfirmPassword($data['confirmPassword']);
        $Agent->setCity( $data['city']);
        $Agent->setStatus('Waiting');

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($Agent);
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
        $Agent=$this->getDoctrine()->getRepository(Agent::class)->find($id);
        
        $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($Agent->getEmail());
        $data=json_decode($request->getContent(),true );
        $Agent->setEmail( $data['email']);
        $Agent->setRoles($data['roles']);
        $Agent->setName($data['name']);
        $Agent->setAdress($data['adress']);
        $Agent->setPhoneNumber( $data['phoneNumber']);
        $Agent->setCountry($data['country']);
        $Agent->setLogo($data['logo']);
        $Agent->setPassword( $data['password']);
        $Agent->setStatus($data['status']);
        $Agent->setConfirmPassword($data['confirmPassword']);
        $Agent->setCity( $data['city']);
        if( $user==null){
            $user=new  User();
            if(($data['status']=='Accepted')){
                $user->setEmail($data['email']);
                $user->setRoles($data['roles']);
                $user->setPassword($data['password']);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($user);
    
    
            }
            
        }else{
            $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($Agent->getEmail());
            if(($data['status']=='Accepted')){
                $user->setEmail($data['email']);
                $user->setRoles($data['roles']);
                $user->setPassword($data['password']);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($user);
    
    
            }else{
                if(($data['status']=='Denied')){
            $entityManager=$this->getDoctrine()->getManager();

            $entityManager->remove($user);
                }
            };
            
            


        }
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($Agent);
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
        foreach($agent  as  $agence)
        {
            $res[]=[
                'id'=>$agence->getId(),
                'email'=>$agence->getEmail(),
                'roles'=>$agence->getRoles(),
                'password'=>$agence->getPassword(),
                'status'=>$agence->getStatus(),
                'adress'=>$agence->getAdress(),
                'phoneNumber'=>$agence->getPhoneNumber(),
                'country'=>$agence->getCountry(),
                'name'=>$agence->getName(),
                'logo'=>$agence->getLogo(),
                'city'=>$agence->getCity(),
                'roles'=>$agence->getRoles(),
                'confirmPaswword'=>$agence->getConfirmPaswword(),
                'status'=>$agence->getStatus(),

                ];
        }

        
       
        return $this->json(
            $res
        );
    }

/**
     * @Route("/agenceEmail/{email}", name="agence_email", methods={"GET"})
     */
    public function OneEmail($email): Response
    {
        $agence=$this->getDoctrine()->getRepository(Agent::class)->findOneByMail($email);
        
        
            $res[]=[
                'id'=>$agence->getId(),
                'email'=>$agence->getEmail(),
                'roles'=>$agence->getRoles(),
                'password'=>$agence->getPassword(),
                'status'=>$agence->getStatus(),
                'adress'=>$agence->getAdress(),
                'phoneNumber'=>$agence->getPhoneNumber(),
                'country'=>$agence->getCountry(),
                'name'=>$agence->getName(),
                'logo'=>$agence->getLogo(),
                'city'=>$agence->getCity(),

                ];
        
        
       
        return $this->json(
            $res
        );
    }

    
  
    
    }

