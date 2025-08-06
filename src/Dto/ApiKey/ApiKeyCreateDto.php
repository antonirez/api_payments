<?php

namespace App\Dto\ApiKey;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ApiKeyCreateDto
{
    #[Assert\NotBlank(message: 'Currency ID is required.')]
    #[Groups(['api_keys:write'])]
    public string $currencyId;

    #[Assert\NotBlank(message: 'API key name cannot be blank.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'The name cannot exceed {{ limit }} characters.'
    )]
    #[Groups(['api_keys:write'])]
    public string $name;

    #[Assert\NotNull(message: 'Enabled status is required.')]
    #[Groups(['api_keys:write'])]
    public bool $enabled = true;
}