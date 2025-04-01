<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Entity\Inscription;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facture::class;
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
    /* public function configureFields(string $pageName): iterable
    {
        $fields =  [
            IdField::new('id'),
            TextField::new('numFact'),
            TextField::new('nom'),
            NumberField::new('montant'),
        ];
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                $field->addCssFiles('css/easy_admin.css')
                    ->addJsFiles('js/easy_admin.js');
            }
        }

        return $fields;
    }*/
}
