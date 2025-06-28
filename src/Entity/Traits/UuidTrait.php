<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(
        name: 'uuid',
        type: 'string',
        length: 36,
        unique: true,
        nullable: false
    )]
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    #[ORM\PrePersist]
    public function initializeUuid(): void
    {
        if (!isset($this->uuid)) {
            $this->uuid = Uuid::v4()->toRfc4122();
        }
    }
}
