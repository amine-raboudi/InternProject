<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Agent;
use App\Entity\Admin;
use App\Form\AgentType;
use App\Form\AdminType;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;




class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(MailerInterface $emailVerifier,EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
    }

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
                            $Agent,
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
     * @Route("register/client", name="app_client_register",methods={"POST"})
     */
    public function Client(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): JsonResponse
    {
        $client = new Client();
        $user=new User();

        $data = json_decode($request->getContent(), true);

        // Validate and sanitize the data as per your requirements
        $email = $data['email'];
        $password = $data['password'];

        // Create a new User entity
        $client->setEmail($email);

        $client->setIsVerified(false);
        $client->setRoles(['ROLE_CLIENT']);

        // Encode the password
        $client->setPassword($password);
        $verificationToken = md5(uniqid());
        $client->setVerificationToken($verificationToken);

        // Save the user to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        // Send verification email
       


        $this->sendVerificationEmail($client, $verificationToken);


        return new JsonResponse(['message' => 'User registered successfully! Please check your email for verification.'], 201);
    }

    /**
     * @Route("/verify-email/{userId}/{verificationToken}", name="verify_email", methods={"GET"})
     */
    public function verifyEmail( $userId, string $verificationToken): JsonResponse
    {
        $client= $this->entityManager->getRepository(Client::class)->find($userId);
        if ($client->getVerificationToken() === $verificationToken) {
            $client->setIsVerified(true);
            $client->setVerificationToken(null);

            $user=new User;
            $user->setEmail( $client->getEmail());
            $user->setPassword($client->getPassword());
            $user->setRoles(['ROLE_CLIENT']);
    
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->persist($user);

            $entityManager->flush();

            return new JsonResponse(['message' => 'Email verified successfully!'], 200);
        }

        return new JsonResponse(['error' => 'Invalid verification token.'], 400);
    }

    private function sendVerificationEmail(Client $client, string $verificationToken): void
    {
        $email = (new Email())
            ->from('mohamedamineraboudi@gmail.com')
            ->to($client->getEmail())
            ->subject('Email Verification')
            ->html("
            <h3>Verify Your Email</h3>
            <p>Please click the following link to verify your email:</p>
            <p><a href='http://localhost:8000/verify-email/{$client->getId()}/{$verificationToken}'>Verify Email</a></p>
        ");
        $this->emailVerifier->send($email);
    }




    /**
     * @Route("/register/admin", name="app_admin_register")
     */
    public function Admin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
       
            $admin = new Admin();
            $user=new User();
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

                $user->setEmail($admin->getEmail());
                $user->setRoles($admin->getRoles());
                $user->setPassword($admin->getPassword());

                $entityManager->persist($admin);

               
                
                $entityManager->flush();


    
                return $this->redirectToRoute('app_check');
            }
    
            return $this->render('agent/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    
    
}