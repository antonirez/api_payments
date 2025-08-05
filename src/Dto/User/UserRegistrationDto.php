<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['users:write'])]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    #[Groups(['users:write'])]
    public string $plainPassword;
}