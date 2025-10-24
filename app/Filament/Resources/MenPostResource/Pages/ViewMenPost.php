<?php

declare(strict_types=1);

namespace App\Filament\Resources\MenPostResource\Pages;

use App\Filament\Resources\MenPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * View men post page for Filament admin panel
 * 
 * @package App\Filament\Resources\MenPostResource\Pages
 */
final class ViewMenPost extends ViewRecord
{
    protected static string $resource = MenPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}


