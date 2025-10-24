<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MenPostResource\Pages;
use App\Models\MenPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Men post resource for Filament admin panel
 * 
 * Provides CRUD operations for men post management
 * including moderation, flag breakdown, and content review.
 * 
 * @package App\Filament\Resources
 */
final class MenPostResource extends Resource
{
    protected static ?string $model = MenPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TagsInput::make('tags')
                    ->placeholder('Add tags'),
                Forms\Components\Textarea::make('caption')
                    ->required()
                    ->maxLength(2000)
                    ->rows(4),
                Forms\Components\TextInput::make('photo_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\KeyValue::make('flag_counts')
                    ->keyLabel('Flag Type')
                    ->valueLabel('Count'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flag_counts')
                    ->formatStateUsing(fn ($state) => 
                        'Red: ' . ($state['red'] ?? 0) . 
                        ' | Green: ' . ($state['green'] ?? 0) . 
                        ' | Neutral: ' . ($state['neutral'] ?? 0)
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('high_red_flags')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereRaw("JSON_EXTRACT(flag_counts, '$.red') > 5")
                    ),
                Tables\Filters\Filter::make('recent')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('created_at', '>=', now()->subDays(7))
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (MenPost $record) => $record->update(['approved_at' => now()])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (MenPost $record) => $record->update(['rejected_at' => now()])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['approved_at' => now()])),
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
            'index' => Pages\ListMenPosts::route('/'),
            'create' => Pages\CreateMenPost::route('/create'),
            'view' => Pages\ViewMenPost::route('/{record}'),
            'edit' => Pages\EditMenPost::route('/{record}/edit'),
        ];
    }
}


