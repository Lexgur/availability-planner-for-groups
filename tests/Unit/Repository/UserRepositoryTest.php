<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::getContainer()->get(UserRepository::class);
    }

    public function testUpgradePassword(): void
    {
        $user = new User();
        $user->setEmail('test@example.example');
        $user->setEmailHashFromEmail('test@example.example');
        $user->setPassword('old_hashed');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $newHashedPassword = 'new_hashed_password';
        $this->repository->upgradePassword($user, $newHashedPassword);

        $this->assertSame($newHashedPassword, $user->getPassword());
    }

    /**
     * @throws Exception
     */
    public function testUpgradePasswordThrowsForUnsupportedUser(): void
    {
        $this->expectException(UnsupportedUserException::class);

        $fakeUser = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $this->repository->upgradePassword($fakeUser, 'irrelevant');
    }
}
