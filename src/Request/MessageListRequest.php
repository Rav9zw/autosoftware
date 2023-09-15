<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class MessageListRequest
{
    #[Assert\Choice(['name', 'uuid'])]
    public string $sortBy;

    #[Assert\Choice(['asc', 'desc'])]
    public string $orderBy;
}