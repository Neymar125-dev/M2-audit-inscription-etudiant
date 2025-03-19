<?php

namespace App\EventSubscriber;

use App\Entity\AuditInscription;
use App\Entity\Inscription;
use DateTimeImmutable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class InscriptionSubscriber implements EventSubscriber
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
        if (!$entity instanceof Inscription) {
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
        if (!$entity instanceof Inscription) {
            return;
        }
        //recuperer les valeurs actuels
        $currentData = $this->entityToArray($entity);

        //vérifier les changements et conserver les valeurs non modifié
        $new = [];
        $old = [];

        //Matricule 
        if ($args->hasChangedField('matricule')) {
            $old['matricule'] = $args->getOldValue('matricule');
            $new['matricule'] = $args->getNewValue('matricule');
        } else {
            $old['matricule'] = $currentData['matricule'];
            $new['matricule'] = $currentData['matricule'];
        }

        //Nom
        if ($args->hasChangedField('nom')) {
            $old['nom'] = $args->getOldValue('nom');
            $new['nom'] = $args->getNewValue('nom');
        } else {
            $old['nom'] = $currentData['nom'];
            $new['nom'] = $currentData['nom'];
        }

        //Droit
        if ($args->hasChangedField('droit')) {
            $old['droit'] = $args->getOldValue('droit');
            $new['droit'] = $args->getNewValue('droit');
        } else {
            $old['droit'] = $currentData['droit'];
            $new['droit'] = $currentData['droit'];
        }
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Inscription) {
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
        if (!$entity instanceof Inscription) {
            return;
        }

        $this->logAudit($args, 'insert', null, $this->entityToArray($entity));
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Inscription) {
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
            if (!isset($changes['matricule'])) {
                $old['matricule'] = $currentData['matricule'];
                $new['matricule'] = $currentData['matricule'];
            }
            if (!isset($changes['nom'])) {
                $old['nom'] = $currentData['nom'];
                $new['nom'] = $currentData['nom'];
            }
            if (!isset($changes['droit'])) {
                $old['droit'] = $currentData['droit'];
                $new['droit'] = $currentData['droit'];
            }

            $this->logAudit($args, 'update', $old, $new);
        }
    }

    private function entityToArray(Inscription $entity): array
    {
        return [
            'matricule' => $entity->getMatricule(),
            'nom' => $entity->getNom(),
            'droit' => $entity->getDroit()
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
                    $audit = new AuditInscription();
                    $audit->setTypeAction('INSERT');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setMatricule($new['matricule']);
                    $audit->setNom($new['nom']);
                    $audit->setDroitAncien($new['droit'] ?? null);
                    $audit->setDroitNouveau($new['droit']);
                    $audit->setUtilisateur($this->getCurrentUser());
                    break;
                case 'update':
                    $audit = new AuditInscription();
                    $audit->setTypeAction('UPDATE');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setMatricule($new['matricule']);
                    $audit->setNom($new['nom']);
                    $audit->setDroitAncien($old['droit'] ?? null);
                    $audit->setDroitNouveau($new['droit']);
                    $audit->setUtilisateur($this->getCurrentUser());
                    break;
                case 'delete':
                    $audit = new AuditInscription();
                    $audit->setTypeAction('DELETE');
                    $audit->setUpdatedAt(new DateTimeImmutable());
                    $audit->setMatricule($old['matricule']);
                    $audit->setNom($old['nom']);
                    $audit->setDroitAncien($old['droit'] ?? null);
                    $audit->setDroitNouveau($old['droit']);
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
