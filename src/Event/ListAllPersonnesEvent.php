<?php
/*
 * Copyright (c) 2024.
 *
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonnesEvent extends Event
{
    const LIST_ALL_PERSONNE_EVENT= 'personne.list';

    public function __construct(private int $nbrePersonnes)
    {
    }

    /**
     * @return int
     */
    public function getNbrePersonnes(): int
    {
        return $this->nbrePersonnes;
    }
}