<?php

namespace App\Helpers;

enum SuggestionStatusEnum: string
{

    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';
}
