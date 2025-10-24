<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedPostResource\Pages;

use App\Filament\Resources\FeedPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * List feed posts page for Filament admin panel
 * 
 * @package App\Filament\Resources\FeedPostResource\Pages
 */
final class ListFeedPosts extends ListRecords
{
    protected static string $resource = FeedPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}


