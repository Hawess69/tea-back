<?php

declare(strict_types=1);

namespace App\Filament\Resources\MenPostResource\Pages;

use App\Filament\Resources\MenPostResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Create men post page for Filament admin panel
 * 
 * @package App\Filament\Resources\MenPostResource\Pages
 */
final class CreateMenPost extends CreateRecord
{
    protected static string $resource = MenPostResource::class;
}


