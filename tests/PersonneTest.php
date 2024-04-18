<?php
/*
 * Copyright (c) 2024.
 *
 */

namespace App\Tests;

use App\Entity\Personne;
use PHPUnit\Framework\TestCase;

class PersonneTest extends TestCase
{
    public function testSpamScoreWithInvalidRequest():void{
        $this->assertEquals(2,1+1);

    }
    public function testgetSalaireInf1(): void
    {
        $personne = new Personne();
        $resultat= $personne->getSalaire(1);
        $this->assertSame(1800,$resultat);

    }
    public function testgetSalaireInf2(): void
    {
        $personne = new Personne();
        $resultat= $personne->getSalaire(2);
        $this->assertSame(2400,$resultat);

    }
    public function testgetSalaireNegative(): void
    {
        $personne = new Personne();
        $resultat= $personne->getSalaire(-1);
        $this->expectException('LogicException');
    }

}