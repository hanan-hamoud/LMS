<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class InstructorResource extends BaseResource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.Instructor');
    }

    public static function getModelLabel(): string
    {
        return __('instructors.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('instructors.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('instructors.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label(__('instructors.email'))
                    ->required()
                    ->email()
                    ->unique('instructors', 'email', ignoreRecord: true)
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => __('instructors.validation.required'),
                        'email' => __('instructors.validation.email'),
                        'unique' => __('instructors.validation.unique'),
                    ]),

                Forms\Components\TextInput::make('bio')
                    ->label(__('instructors.bio'))
                    ->required()
                    ->maxLength(255),

                FileUpload::make('photo')
                    ->label(__('instructors.photo'))
                    ->storeFiles()
                    ->directory('instructors')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->maxSize(2048),

                Forms\Components\Toggle::make('status')
                    ->label(__('instructors.status'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('instructors.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('instructors.email'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('bio')
                    ->label(__('instructors.bio'))
                    ->searchable(),

                ImageColumn::make('photo')
                    ->label(__('instructors.photo'))
                    ->disk('public')
                    ->height(60)
                    ->width(60)
                    ->circular(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('instructors.status'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('instructors.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('instructors.updated_at'))
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
