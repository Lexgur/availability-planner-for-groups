<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\IsVerifiedTrait;
use App\Entity\Traits\PasswordTrait;
use App\Entity\Traits\RolesTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;
    use EmailTrait;
    use IsVerifiedTrait;
    use PasswordTrait;
    use RolesTrait;
    use UpdatedAtTrait;
    use UuidTrait;

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @phpstan-ignore-next-line  */
    #[CodeCoverageIgnore]
    public function eraseCredentials(): void
    {
        // Clear sensitive data if needed
    }
}
