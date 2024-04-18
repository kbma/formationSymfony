<?php
#src\EventListener\PersonneListener.php
namespace App\EventListener;

use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $loggerInterface)
    {
    }
    public function onPersonneAdd(AddPersonneEvent $addPersonneEvent)
    {
        $this->loggerInterface->debug('Je suis entain d\'ecouté l\'événement personne.add et une personne est vient détre ajouté ' . $addPersonneEvent->getPersonne()->getName());
    }
    public function onListPersonneCount(ListAllPersonnesEvent $listAllPersonnesEvent)
    {
        $this->loggerInterface->debug('Le nbre actuel des personnes est ' . $listAllPersonnesEvent->getNbrePersonnes());

    }
}
