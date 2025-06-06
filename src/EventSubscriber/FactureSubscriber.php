<?php

namespace App\EventSubscriber;

use App\Entity\AuditFacture;
use App\Entity\Facture;
use DateTimeImmutable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class FactureSubscriber implements EventSubscriber
{
    private array $temp = [];
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Facture) {
            return;
        }
        $this->temp[] = [
            'id' => $entity->getId(),
            'data' => $this->entityToArray($entity),
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Facture) {
            return;
        }
        //recuperer les valeurs actuels
        $currentData = $this->entityToArray($entity);

        //vérifier les changements et conserver les valeurs non modifié
        $new = [];
        $old = [];

        //Numéro facture 
        if ($args->hasChangedField('numero')) {
            $old['numero'] = $args->getOldValue('numero');
            $new['numero'] = $args->getNewValue('numero');
        } else {
            $old['numero'] = $currentData['numero'];
            $new['numero'] = $currentData['numero'];
        }

        //Nom
        if ($args->hasChangedField('nom')) {
            $old['nom'] = $args->getOldValue('nom');
            $new['nom'] = $args->getNewValue('nom');
        } else {
            $old['nom'] = $currentData['nom'];
            $new['nom'] = $currentData['nom'];
        }

        //Montant
        if ($args->hasChangedField('montant')) {
            $old['montant'] = $args->getOldValue('montant');
            $new['montant'] = $args->getNewValue('droit');
        } else {
            $old['montant'] = $currentData['montant'];
            $new['montant'] = $currentData['montant'];
        }
        //Date
        if ($args->hasChangedField('date')) {
            $old['date'] = $args->getOldValue('date');
            $new['date'] = $args->getNewValue('date');
        } else {
            $old['date'] = $currentData['date'];
            $new['date'] = $currentData['date'];
        }
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Facture) {
            return;
        }

        $removedData = array_shift($this->temp);
        if (!$removedData) {
            return;
        }

        $this->logAudit($args, 'delete', $removedData['data'], []);
    }


    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Facture) {
            return;
        }

        $this->logAudit($args, 'insert', null, $this->entityToArray($entity));
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Facture) {
            return;
        }
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $changes = $unitOfWork->getEntityChangeSet($entity);
        if (!empty($changes)) {
            $old = [];
            $new = [];
            $currentData = $this->entityToArray($entity);
            foreach ($changes as $field => $change) {
                $old[$field] = $change[0];
                $new[$field] = $change[1];
            }
            //conserver les valeurs non modifier// Conserver les valeurs non modifiées
            if (!isset($changes['numero'])) {
                $old['numero'] = $currentData['numero'];
                $new['numero'] = $currentData['numero'];
            }
            if (!isset($changes['nom'])) {
                $old['nom'] = $currentData['nom'];
                $new['nom'] = $currentData['nom'];
            }
            if (!isset($changes['montant'])) {
                $old['montant'] = $currentData['montant'];
                $new['montant'] = $currentData['montant'];
            }
            if (!isset($changes['date'])) {
                $old['date'] = $currentData['date'];
                $new['date'] = $currentData['date'];
            }

            $this->logAudit($args, 'update', $old, $new);
        }
    }

    private function entityToArray(Facture $entity): array
    {
        return [
            'numero' => $entity->getNumero(),
            'nom' => $entity->getNom(),
            'montant' => $entity->getMontant(),
            'date' => $entity->getDate()
        ];
    }

    private function logAudit(LifecycleEventArgs $args, string $action, ?array $old, array $new): void
    {
        $conn = $this->entityManager->getConnection();
        $entityManager = $args->getObjectManager();

        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \RuntimeException('EntityManager non valide');
        }

        try {
            $conn->beginTransaction();

            switch ($action) {
                case 'insert':
                    $audit = new AuditFacture();
                    $audit->setTypeAction('INSERT');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setNumero($new['numero']);
                    $audit->setNom($new['nom']);
                    $audit->setMontantAncien($new['montant'] ?? null);
                    $audit->setMontantNouveau($new['montant']);
                    $audit->setUtilisateur($this->getCurrentUser());
                    break;
                case 'update':
                    $audit = new AuditFacture();
                    $audit->setTypeAction('UPDATE');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setNumero($new['numero']);
                    $audit->setNom($new['nom']);
                    $audit->setMontantAncien($old['montant'] ?? null);
                    $audit->setMontantNouveau($new['montant']);
                    $audit->setUtilisateur($this->getCurrentUser());
                    break;
                case 'delete':
                    $audit = new AuditFacture();
                    $audit->setTypeAction('DELETE');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setNumero($old['numero']);
                    $audit->setNom($old['nom']);
                    $audit->setMontantAncien($old['montant'] ?? null);
                    $audit->setMontantNouveau($old['montant']);
                    $audit->setUtilisateur($this->getCurrentUser());
                    break;
                default:
                    return;
            }

            $entityManager->persist($audit);
            $entityManager->flush();
            $conn->commit();
        } catch (\Exception $e) {
            if ($conn->isTransactionActive()) {
                $conn->rollBack();
            }
            throw new \RuntimeException(
                sprintf(
                    "Erreur d'audit pour l'action '%s': %s",
                    $action,
                    $e->getMessage()
                ),
                0,
                $e
            );
        }
    }
    private function getCurrentUser(): string
    {
        $user = $this->security->getUser();
        return $user ? $user->getUserIdentifier() : 'system';
    }
}
