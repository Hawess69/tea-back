<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedPostResource\Pages;

use App\Filament\Resources\FeedPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Edit feed post page for Filament admin panel
 * 
 * @package App\Filament\Resources\FeedPostResource\Pages
 */
final class EditFeedPost extends EditRecord
{
    protected static string $resource = FeedPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}


