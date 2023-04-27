<?php

namespace App\Controller\Admin;
use App\Repository\ClientRepository;
use App\Repository\AgentRepository;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\Agent;
use App\Entity\NewAdmin;
use App\Entity\Admin;
use Symfony\Component\Mailer\MailerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mime\Email;



class DashboardController extends AbstractDashboardController
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
     * @Route("/super/admin", name="super_admin")
     */
    public function index(): Response
    {
        
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
            'countClient' => $this->ClientRepository->countAllClient(),
            'countAgent'=>$this->agentRepository->countAllAgent(),
            'countAdmin'=>$this->adminRepository->countAllAdmin(),
            'CountSupUser'=>$this->userRepository->countAllSupAdmin('["ROLE_SUPER_ADMIN"]'),
           

        ]);
    }
       

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('InternProject');
    }
   
    public function configureMenuItems(): iterable
    {
       $nbAg=$this->agentRepository->countAllAgent();
       $Ag=implode($nbAg);
       $nbCl=$this->ClientRepository->countAllClient();
       $Cl=implode($nbCl);
       $nbAd=$this->adminRepository->countAllAdmin();
       $Ad=implode($nbAd);
       $nbSA=$this->userRepository->countAllSupAdmin('["ROLE_SUPER_ADMIN"]');
       $SA=implode($nbAd);


        $AllUs=intval($Ad)+ intval($Ag)+intval($Cl)+intval($SA);
      
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section();
        yield MenuItem::subMenu('AllUsers', 'fa fa-user')->setSubItems([
             MenuItem::linkToCrud('Agencies', 'fas fa-building', Agent::class)
             ->setBadge($Ag,'danger'),
             MenuItem::linkToCrud('Clients', 'fas fa-user', Client::class)
             ->setBadge($Cl,'danger'),
             MenuItem::linkToCrud('Admins', 'fas fa-user-tie', Admin::class)
             ->setBadge($Ad,'danger'),
             MenuItem::linkToCrud('Add-Admins', 'fas fa-plus', NewAdmin::class)
             ->setBadge('NEW', 'background: transparent; color: blue;')
           
           
        ]);


    }
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getUsername())
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)

            // you can return an URL with the avatar image
            
            // use this method if you don't want to display the user image
            
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getEmail())
            ->displayUserAvatar(true)

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', 'admin', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                
            ]);
    }
    
}
