<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\HashedEmailTrait;
use App\Entity\Traits\IsVerifiedTrait;
use App\Entity\Traits\PasswordTrait;
use App\Entity\Traits\RolesTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL_HASH', fields: ['emailHash'])]
#[UniqueEntity(fields: ['emailHash'], message: 'There is already an account with this email')]
class User implements PasswordAuthenticatedUserInterface
{
    use EmailTrait;
    use HashedEmailTrait;
    use IsVerifiedTrait;
    use PasswordTrait;
    use RolesTrait;
    use TimestampableTrait;
    use UuidTrait;

    public function getUserIdentifier(): string
    {
        return (string) $this->emailHash;
    }

    /** @phpstan-ignore-next-line  */
    #[CodeCoverageIgnore]
    public function eraseCredentials(): void
    {
        // Clear sensitive data if needed
    }
}
