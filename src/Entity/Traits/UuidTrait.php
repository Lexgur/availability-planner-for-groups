<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\uid\Uuid;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $uuid;

    public function initializeUuid(): void
    {
        $this->uuid = Uuid::v4()->toRfc4122();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
