<?php

namespace App\Dto\Merchant;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class MerchantCreateDto
{
    #[Assert\NotBlank(message: 'Name cannot be blank.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot exceed {{ limit }} characters.'
    )]
    #[Groups(['merchants:write'])]
    public string $name;

    #[Assert\NotNull(message: 'Configuration is required.')]
    #[Assert\Type(
        type: 'array',
        message: 'Configuration must be an associative array.'
    )]
    #[Groups(['merchants:write'])]
    public array $config;

    public function __construct(string $name = '', array $config = [])
    {
        $this->name = $name;
        $this->config = $config;
    }
}