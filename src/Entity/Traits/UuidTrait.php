<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(
        type: UuidType::NAME,
        length: 36,
        unique: true,
        nullable: false
    )]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
