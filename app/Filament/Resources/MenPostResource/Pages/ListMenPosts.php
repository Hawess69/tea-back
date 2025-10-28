<?php

declare(strict_types=1);

namespace App\Filament\Resources\MenPostResource\Pages;

use App\Filament\Resources\MenPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * List men posts page for Filament admin panel
 * 
 * @package App\Filament\Resources\MenPostResource\Pages
 */
final class ListMenPosts extends ListRecords
{
    protected static string $resource = MenPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}


