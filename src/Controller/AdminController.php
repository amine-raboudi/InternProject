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
use App\Repository\ClientRepository;
use App\Repository\AgentRepository;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{
   /**
     * @var ClientRepository
     */
    protected  $ClientRepository;

     /**
     * @var AgentRepository
     */
    protected  $agentRepository;
    
     /**
     * @var AdminRepository
     */
    protected  $adminRepository;

    /**
     * @var UserRepository
     */
    protected  $userRepository;
    

public function __construct(ClientRepository $ClientRepository,AgentRepository $agentRepository,AdminRepository $adminRepository,UserRepository $userRepository){
    $this->ClientRepository=$ClientRepository;
    $this->agentRepository=$agentRepository;
    $this->adminRepository=$adminRepository;
    $this->userRepository=$userRepository;

    

}


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
     * @Route("/admin/management/", name="app_admin_management")
     */
    public function index(): Response
    {
        return new RedirectResponse('http://127.0.0.1:4200/admin');
    }
    
    
}
