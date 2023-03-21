<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\AgentRepository;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;



class AgentCrudController extends AbstractCrudController
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
    public static function getEntityFqcn(): string

    {
        return Agent::class;
       
    }


    public function configureFields(string $pageName): iterable
    {
         return [
             
            
            EmailField::new('email')->setFormTypeOption('disabled','disabled'),
            TextField::new('password')->setFormTypeOption('disabled','disabled'),
            ArrayField::new('roles'),
            TextField::new('Status')
            ->setFormTypeOption('disabled','disabled')
            ->renderAsHtml(),
            
        ];
        

       
    }
   
    
    public function configureActions(Actions $actions):Actions
    {
        
        $Accept = Action::new('Accept', 'Accept', 'fa fa-check')
        ->addCssClass('btn btn-outline-success')
        ->linkToCrudAction('confirmation');

        $Deny = Action::new('Deny', 'Deny', 'fa fa-close')
        ->addCssClass('btn btn-outline-danger')
        ->linkToCrudAction('annulation');
        return $actions
        ->add(Crud::PAGE_INDEX, $Accept)
        ->add(Crud::PAGE_INDEX, $Deny)
        ->disable(Action::NEW);

        
    }
   
        
    public function confirmation(MailerInterface $mailer,AdminContext $context,EntityManagerInterface $entityManager): Response
        {
            
            $agence=$context->getEntity()->getInstance();
            $user=new User;
            $email = (new TemplatedEmail())
                ->from('mohamedamineraboudi@gmail.com')
                ->to($agence->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('mail/agence.html.twig');
    
            $mailer->send($email);
            
            $agence->setStatus('Accepted');

            $user->setEmail($agence->getEmail());
            $user->setPassword($agence->getPassword());
            $user->setRoles($agence->getRoles());
            
            $entityManager->persist($user);
            $entityManager->persist($agence);
            $entityManager->flush();
           
    
            return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
                'countClient' => $this->ClientRepository->countAllClient(),
                'countAgent'=>$this->agentRepository->countAllAgent(),
                'countAdmin'=>$this->adminRepository->countAllAdmin(),
                'CountSupUser'=>$this->userRepository->countAllSupAdmin('["ROLE_SUPER_ADMIN"]'),

            ]);
        }

        public function annulation(MailerInterface $mailer,AdminContext $context,EntityManagerInterface $entityManager):Response
        {
            
            $agence=$context->getEntity()->getInstance();
            
    
            $agence->setStatus('Denied');
            $entityManager->persist($agence);
            $entityManager->flush();
            $this->addFlash(
                'info2',
                'dny Successfully'
               );
            return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
                'countClient' => $this->ClientRepository->countAllClient(),
                'countAgent'=>$this->agentRepository->countAllAgent(),
                'countAdmin'=>$this->adminRepository->countAllAdmin(),
                'CountSupUser'=>$this->userRepository->countAllSupAdmin('["ROLE_SUPER_ADMIN"]'),

            ]);
        }
    
        }

