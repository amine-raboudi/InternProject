<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;




class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined()

            
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
           
            BooleanField::new('isVerified')->renderAsSwitch(false )
            ->setFormTypeOption('disabled','disabled')
            ->setValue('black'),
            EmailField::new('email')->setFormTypeOption('disabled','disabled'),
            TextField::new('password')->setFormTypeOption('disabled','disabled'),
            ArrayField::new('roles'),
            

            
        ];
    }
   
    public function configureActions(Actions $actions):Actions
    {
        
        return $actions
        ->disable(Action::DELETE,Action::NEW,Action::EDIT)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;

    }

    
}
