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
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
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
     * @Route("register/client", name="app_client_register")
     */
    public function Client(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $user=new User();

        $form = $this->createForm(RegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $client->setPassword(
            $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('plainPassword')->getData()
                )
            );
        
            $client->setRoles(['ROLE_CLIENT']);
            $client->setIsVerified(false);
            $entityManager->persist($client);

            
            $user->setEmail($client->getEmail());
            $user->setPassword($client->getPassword());
            $user->setRoles($client->getRoles());
            $entityManager->persist($user);
            
            $entityManager->flush();
            

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user ,
                (new TemplatedEmail())
                    ->from(new Address('mailer@gmail.com', 'Client'))
                    ->to($client->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
          
            return $this->redirectToRoute('app_confirmation');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, ClientRepository  $clientRepository): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser(),$clientRepository);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_login');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');
       
        return $this->redirectToRoute('app_client');
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
