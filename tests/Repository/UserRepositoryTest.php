<?php
/*
 * Copyright (c) 2024.
 *
 */

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    public function testCount(){
        $kernel = self::bootKernel();
        $nbreUsers =$kernel->getContainer()->get(UserRepository::class)->count([]);
        $this->assertEquals(10,$nbreUsers);
    }
}