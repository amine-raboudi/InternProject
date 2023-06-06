<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\NewAdmin;
use App\Form\AdminType;
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
use App\Repository\ClientRepository;
use App\Repository\AgentRepository;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{
   





 
    /**
     * @Route("/admin/{id}", name="admin_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $admin =$this->getDoctrine()->getRepository(Admin::class)->find($id);
  
        if (!$admin) {
  
            return $this->json('No agence found for id' . $id, 404);
        }
  
         $res[]=[
                'id'=>$admin->getId(),
                'email'=>$admin->getEmail(),
                'roles'=>$admin->getRoles(),
                'password'=>$admin->getPassword(),
                'adress'=>$admin->getAdress(),
                'phoneNumber'=>$admin->getPhoneNumber(),
                'country'=>$admin->getCountry(),
                'name'=>$admin->getName(),
                'logo'=>$admin->getLogo(),
                'city'=>$admin->getCity(),
                'confirmPassword'=>$admin->getConfirmPassword(),
                'status'=>$admin->getStatus(),
                ];
          
        return $this->json($res);
    }
      /**
     * @Route("/newAdmin/post", name="admin_post", methods={"POST"})
     */
    public function post(Request $request): Response
    {
        $admin=new NewAdmin;
        $param=json_decode($request->getContent(),true );
        $admin->setEmail($param['email']);
        $admin->setMailSended($param['MailSended']);
        

        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }
    

     /**
     * @Route("/admin/update/{id}", name="admin_update", methods={"PUT"})
     */
    public function update(Request $request,$id): Response
    {
        $admin=$this->getDoctrine()->getRepository(Admin::class)->find($id);
        $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($admin->getEmail());
        $data=json_decode($request->getContent(),true );
        $admin->setEmail( $data['email']);
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setName($data['name']);
        $admin->setAdress($data['adress']);
        $admin->setPhoneNumber( $data['phoneNumber']);
        $admin->setCountry($data['country']);
        $admin->setLogo($data['logo']);
        $admin->setPassword( $data['password']);
        $admin->setConfirmPassword($data['confirmPassword']);
        $admin->setCity( $data['city']);
        $admin->setStatus($data['status']);
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
            $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($admin->getEmail());
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
        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }
    /**
     * @Route("/admin/delete/{id}", name="admin_delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
    $admin=$this->getDoctrine()->getRepository(Admin::class)->find($id);
    $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($admin->getEmail());


        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($admin);
        $entityManager->remove($user);

        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }

     /**
     * @Route("/adminAll", name="admin_list", methods={"GET"})
     */
    public function list(): Response
    {
        $ad=$this->getDoctrine()->getRepository(Admin::class)->findAll();
        foreach($ad  as  $admin)
        {
            $res[]=[
                'id'=>$admin->getId(),
                'email'=>$admin->getEmail(),
                'roles'=>$admin->getRoles(),
                'password'=>$admin->getPassword(),
                'adress'=>$admin->getAdress(),
                'phoneNumber'=>$admin->getPhoneNumber(),
                'country'=>$admin->getCountry(),
                'name'=>$admin->getName(),
                'logo'=>$admin->getLogo(),
                'city'=>$admin->getCity(),
                'confirmPassword'=>$admin->getConfirmPassword(),
                'status'=>$admin->getStatus(),
                ];
        }

        
       
        return $this->json(
            $res
        );
    }
 /**
     * @Route("/newAdmin", name="newAdmin", methods={"GET"})
     */
    public function newAdmin(): Response
    {
        $admin=$this->getDoctrine()->getRepository(NewAdmin::class)->findAll();
        foreach($admin  as  $d)
        {
            $res[]=[
                'id'=>$d->getId(),
                'email'=>$d->getEmail(),
                'MailSended'=>$d->isMailSended(),
                
                ];
        }

        
       
        return $this->json(
            $res
        );
    }

/**
     * @Route("/newAdmin/update/{id}", name="newAdmin_update", methods={"PUT"})
     */
    public function updateNew(Request $request,$id): Response
    {
        $admin=$this->getDoctrine()->getRepository(NewAdmin::class)->find($id);
        $param=json_decode($request->getContent(),true );
        $admin->setEmail($param['email']);
        $admin->setMailSended($param['MailSended']);


        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json(
            'OK!!!!!'
        );
    }
    
      /**
     * @Route("/adminEmail/{email}", name="admin_email", methods={"GET"})
     */
    public function OneEmail($email): Response
    {
        $admin=$this->getDoctrine()->getRepository(Admin::class)->findOneByMail($email);
        
        
            $res[]=[
                'id'=>$admin->getId(),
                'email'=>$admin->getEmail(),
                'roles'=>$admin->getRoles(),
                'password'=>$admin->getPassword(),
                'adress'=>$admin->getAdress(),
                'phoneNumber'=>$admin->getPhoneNumber(),
                'country'=>$admin->getCountry(),
                'name'=>$admin->getName(),
                'logo'=>$admin->getLogo(),
                'city'=>$admin->getCity(),
                'confirmPassword'=>$admin->getConfirmPassword(),
                'status'=>$admin->getStatus(),
                ];
        
        
       
        return $this->json(
            $res
        );
    }

    
  
}
