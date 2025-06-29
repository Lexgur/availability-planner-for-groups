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
#[ORM\HasLifecycleCallbacks]
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
        return (string)$this->email;
    }

    #[ORM\PrePersist]
    public function initializeTimestamps(): void
    {
        $now = new \DateTimeImmutable();

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($now);
        }

        if ($this->getUpdatedAt() === null) {
            $this->setUpdatedAt($now);
        }
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    /** @phpstan-ignore-next-line  */
    #[CodeCoverageIgnore]
    public function eraseCredentials(): void
    {
        // Clear sensitive data if needed
    }
}
