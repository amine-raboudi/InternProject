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
     * @Route("/register/agent", name="app_agent_register")
     */
    public function agent(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
       
            $Agent = new Agent();
            $form = $this->createForm(AgentType::class, $Agent);
            $form->handleRequest($request);
            
    
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $Agent->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                $Agent->setRoles(['ROLE_AGENT']);
                $Agent->setStatus('Waiting');
                $entityManager->persist($Agent);

                
                
                $entityManager->flush();


    
                return $this->redirectToRoute('app_check');
            }
    
            return $this->render('agent/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        /**
     * @Route("/agence/management", name="app_agence_management")
     */
    public function index(): Response
    {
        return $this->render('agence_management/index.html.twig', [
            'controller_name' => 'AgenceManagementController',
        ]);
    }
    
    }

