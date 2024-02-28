<?php

namespace App\Type;

enum HealthStatus: string
{
    case HEALTHY = 'Healthy';
    case SICK = 'SICK';
}
