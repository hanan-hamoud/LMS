<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\Pages;
use App\Models\CourseCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\BaseResource;
class CourseCategoryResource extends BaseResource
{
    protected static ?string $model = CourseCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'course_categories.plural';
    protected static ?string $pluralModelLabel = 'course_categories.plural';

    public static function getNavigationLabel(): string
    {
        return __('course_categories.plural');
    }

    public static function getPluralModelLabel(): string
    {
        return __('course_categories.plural');
    }

    public static function getModelLabel(): string
    {
        return __('course_categories.singular');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('course_categories.name'))
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (callable $set, $state) {
                    $set('slug', Str::slug($state));
                }),

            Forms\Components\TextInput::make('slug')
                ->label(__('course_categories.slug'))
                ->required()
                ->readonly()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\Toggle::make('status')
                ->label(__('course_categories.active'))
                ->required()
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('course_categories.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('course_categories.slug'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('course_categories.active'))
                    ->boolean()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('course_categories.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('course_categories.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('course_categories.filter_by_status'))
                    ->options([
                        1 => __('course_categories.active'),
                        0 => __('course_categories.inactive'),
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCourseCategories::route('/'),
            'create' => Pages\CreateCourseCategory::route('/create'),
            'edit' => Pages\EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
