<?php

namespace App\Controller\Admin;

use App\Entity\AuditInscription;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;


class AuditInscriptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AuditInscription::class;
    }

    /*public function configureAssets(): array
    {
        return [
            CssAsset::new('css/easy_admin.css'),
            JsAsset::new('js/easy_admin.js'),
        ];
    }*/

    public function configureFields(string $pageName): iterable
    {
        $fields =  [
            IdField::new('id'),
            TextField::new('typeAction'),
            TextField::new('matricule'),
            TextField::new('nom'),
            NumberField::new('droitAncien'),
            NumberField::new('droitNouveau'),
            TextField::new('utilisateur'),
            DateTimeField::new('updated_At'),
        ];
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                $field->addCssFiles('css/easy_admin.css')
                    ->addJsFiles('js/easy_admin.js');
            }
        }

        return $fields;
    }
}
