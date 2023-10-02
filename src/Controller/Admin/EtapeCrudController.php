<?php

namespace App\Controller\Admin;

use App\Entity\Etape;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtapeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etape::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
