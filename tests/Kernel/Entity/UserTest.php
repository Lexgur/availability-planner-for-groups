<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
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
        $this->assertNotEmpty($user->getPassword());
        $this->assertEquals('test@example.test', $user->getEmail());

        // Fetch user from database by UUID
        $repoUser = $this->userRepository->find($user->getUuid());

        $this->assertNotEmpty($user->getUserIdentifier());
        $this->assertSame($user->getUuid(), $repoUser->getUuid());
        $this->assertSame($user->getEmail(), $repoUser->getEmail());
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

        $updatedUser = $this->userRepository->find($user->getUuid());

        $this->assertNotSame($oldPassword, $updatedUser->getPassword());
        $this->assertEquals('updated@example.updated', $updatedUser->getEmail());
        $this->assertEquals('ROLE_USER', $updatedUser->getRoles()[0]);
        $this->assertTrue($updatedUser->isVerified());
        $this->assertNotEmpty($updatedUser->getUpdatedAt());
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

        $deletedUser = $this->userRepository->find($uuid);

        $this->assertNull($deletedUser);
    }

    public function testIncorrectEmailIsNotAllowedByValidator(): void
    {
        $user = new User();
        $user->setEmail('invalid-email');
        $user->setPassword(password_hash('password123', \PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        $violations = $this->validator->validate($user);

        $this->assertGreaterThan(0, \count($violations), 'This is checking if the validator returns any violations.');

        //This filters through the violations, makes sure it's an 'email' violation
        $emailViolation = array_filter(iterator_to_array($violations), fn($violation) => 'email' === $violation->getPropertyPath());
        $this->assertNotEmpty($emailViolation);
    }
}
