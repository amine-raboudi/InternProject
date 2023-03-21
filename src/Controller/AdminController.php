<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
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


class AdminController extends AbstractController
{
    /**
     * @Route("/register/admin", name="app_admin_register")
     */
    public function Admin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
       
            $admin = new Admin();
            $form = $this->createForm(AdminType::class, $admin);
            $form->handleRequest($request);
            
    
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $admin->setPassword(
                    $userPasswordHasher->hashPassword(
                            $admin,
                            $form->get('plainPassword')->getData()
                        )
                    );
                $admin->setRoles(['ROLE_ADMIN']);
                $admin->setStatus('Waiting');

                $entityManager->persist($admin);

               
                
                $entityManager->flush();


    
                return $this->redirectToRoute('app_check');
            }
    
            return $this->render('agent/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }

         /**
     * @Route("/admin/management", name="app_admin_management")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    
}