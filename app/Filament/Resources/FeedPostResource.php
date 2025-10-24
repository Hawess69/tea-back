<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FeedPostResource\Pages;
use App\Models\FeedPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Feed post resource for Filament admin panel
 * 
 * Provides CRUD operations for feed post management
 * including approval, deletion, and pinning functionality.
 * 
 * @package App\Filament\Resources
 */
final class FeedPostResource extends Resource
{
    protected static ?string $model = FeedPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->maxLength(5000)
                    ->rows(4),
                Forms\Components\TextInput::make('image_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('upvotes')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('downvotes')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('comments_count')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('upvotes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('downvotes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comments_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('high_engagement')
                    ->query(fn (Builder $query): Builder => $query->where('upvotes', '>', 10)),
                Tables\Filters\Filter::make('recent')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pin')
                    ->label('Pin to Top')
                    ->icon('heroicon-o-pin')
                    ->color('warning')
                    ->action(fn (FeedPost $record) => $record->update(['pinned_at' => now()])),
                Tables\Actions\Action::make('unpin')
                    ->label('Unpin')
                    ->icon('heroicon-o-pin-slash')
                    ->color('gray')
                    ->action(fn (FeedPost $record) => $record->update(['pinned_at' => null])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('pin')
                        ->label('Pin Selected')
                        ->icon('heroicon-o-pin')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['pinned_at' => now()])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedPosts::route('/'),
            'create' => Pages\CreateFeedPost::route('/create'),
            'view' => Pages\ViewFeedPost::route('/{record}'),
            'edit' => Pages\EditFeedPost::route('/{record}/edit'),
        ];
    }
}


