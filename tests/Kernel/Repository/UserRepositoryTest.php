<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->repository = self::getContainer()->get(UserRepository::class);
    }

    public function testRepositoryIsInstantiated(): void
    {
        $this->assertInstanceOf(UserRepository::class, $this->repository);
    }
}
