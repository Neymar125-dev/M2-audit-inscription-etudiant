<?php

namespace App\Controller\Admin;

use App\Entity\AuditFacture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;


class AuditFactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AuditFacture::class;
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
            TextField::new('numero'),
            TextField::new('nom'),
            NumberField::new('MontantAncien'),
            NumberField::new('MontantNouveau'),
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
