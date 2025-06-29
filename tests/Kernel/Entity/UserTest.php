<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class UserTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testPersistAndRetrieveUser(): void
    {
        $user = new User();
        $user->setEmail('test@example.test');
        $user->setPassword(password_hash('password123', \PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        // Persist and flush to database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertNotEmpty($user->getUuid());
        $this->assertNotEmpty($user->getCreatedAt());
        $this->assertNotEmpty($user->getUpdatedAt());
        $this->assertNotEmpty($user->getPassword());
        $this->assertEquals('test@example.test', $user->getEmail());

        // Fetch user from database by UUID
        $repoUser = $this->entityManager->getRepository(User::class)->find($user->getUuid());

        $this->assertNotEmpty($user->getUserIdentifier());
        $this->assertSame($user->getUuid(), $repoUser->getUuid());
        $this->assertEquals('test@example.test', $repoUser->getEmail());
    }

    public function testUpdateUser(): void
    {
        // Create and persist user
        $user = new User();
        $user->setEmail('original@example.original');
        $oldPassword = $user->setPassword(password_hash('original', \PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Update user
        $user->setEmail('updated@example.updated');
        $user->setPassword(password_hash('updated', \PASSWORD_BCRYPT));
        $user->setVerified(true);

        $this->entityManager->flush();

        $updatedUser = $this->entityManager->getRepository(User::class)->find($user->getUuid());

        $this->assertNotSame($oldPassword, $updatedUser->getPassword());
        $this->assertEquals('updated@example.updated', $updatedUser->getEmail());
        $this->assertEquals('ROLE_USER', $updatedUser->getRoles()[0]);
        $this->assertTrue($updatedUser->isVerified());
        $this->assertNotNull($updatedUser->getUpdatedAt());
    }

    public function testRemoveUser(): void
    {
        $user = new User();
        $user->setEmail('delete@example.delete');
        $user->setPassword(password_hash('secret', \PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $uuid = $user->getUuid();

        // Remove and flush
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $deletedUser = $this->entityManager->getRepository(User::class)->find($uuid);

        $this->assertNull($deletedUser);
    }
}
