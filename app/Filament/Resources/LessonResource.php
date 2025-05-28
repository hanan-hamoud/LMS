<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;

class LessonResource extends BaseResource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.Lesson');
    }

    public static function getModelLabel(): string
    {
        return __('lessons.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('lessons.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course_id')
                    ->label(__('lessons.course_id'))
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('title')
                    ->label(__('lessons.title'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label(__('lessons.description'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('video_url')
                    ->label(__('lessons.video_url'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_preview')
                    ->label(__('lessons.is_preview'))
                    ->required(),

                Forms\Components\TextInput::make('order')
                    ->label(__('lessons.order'))
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_id')
                    ->label(__('lessons.course_id'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('lessons.title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('video_url')
                    ->label(__('lessons.video_url'))
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_preview')
                    ->label(__('lessons.is_preview'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                    ->label(__('lessons.order'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('lessons.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('lessons.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
