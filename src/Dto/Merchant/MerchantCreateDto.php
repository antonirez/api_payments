<?php

namespace App\Dto\Merchant;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class MerchantCreateDto
{
    #[Assert\NotBlank(message: 'El nombre no puede estar vacío')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'El nombre no puede exceder {{ limit }} caracteres'
    )]
    #[Groups(['merchants:write'])]
    public string $name;

    #[Assert\NotNull(message: 'La configuración es obligatoria')]
    #[Assert\Type(
        type: 'array',
        message: 'La configuración debe ser un array asociativo'
    )]
    #[Groups(['merchants:write'])]
    public array $config;

    public function __construct(string $name = '', array $config = [])
    {
        $this->name = $name;
        $this->config = $config;
    }
}
