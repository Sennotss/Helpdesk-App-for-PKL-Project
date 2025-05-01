<?php

namespace App\Enums;
enum AppStatus: string
{
    case ACTIVE = 'active';
    case NONACTIVE = 'non-active';
    case MAINTENANCE = 'maintenance';
}

?>
