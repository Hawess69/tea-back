<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedPostResource\Pages;

use App\Filament\Resources\FeedPostResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Create feed post page for Filament admin panel
 * 
 * @package App\Filament\Resources\FeedPostResource\Pages
 */
final class CreateFeedPost extends CreateRecord
{
    protected static string $resource = FeedPostResource::class;
}


