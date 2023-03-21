<?php

namespace App\Controller\Admin;

use App\Entity\NewAdmin;
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



class NewAdminCrudController extends AbstractCrudController
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
        return NewAdmin::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            BooleanField::new('MailSended')->hideOnForm()->renderAsSwitch(false )
           
        ];
    }

    public function configureActions(Actions $actions):Actions
    {
        
        $confirm = Action::new('Send', 'Send', 'fa fa-check')
        ->addCssClass('btn btn-outline-success')
        ->linkToCrudAction('confirmation');

       
        return $actions
       
        ->add(Crud::PAGE_INDEX, $confirm)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT, Action::DELETE]);
        
        

        
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined()
        ;
    }
    public function confirmation(MailerInterface $mailer,AdminContext $context,EntityManagerInterface $entityManager): Response
        {
            
            $admin=$context->getEntity()->getInstance();
            $email = (new TemplatedEmail())
                ->from('mohamedamineraboudi@gmail.com')
                ->to($admin->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('mail/AddAdmin.html.twig');
    
            $mailer->send($email);
            $admin->setMailSended(true);
            
            $entityManager->persist($admin);
            $entityManager->flush();
            return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
                'countClient' => $this->ClientRepository->countAllClient(),
                'countAgent'=>$this->agentRepository->countAllAgent(),
                'countAdmin'=>$this->adminRepository->countAllAdmin(),
                'CountSupUser'=>$this->userRepository->countAllSupAdmin('["ROLE_SUPER_ADMIN"]'),

            ]);
        }
  
}
