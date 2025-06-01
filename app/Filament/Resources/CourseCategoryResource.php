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
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\BaseResource;
  use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Textarea;
    
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
        return $form
            ->schema([
                TextInput::make('name_en')
                ->label('Name (English)')
                ->required(),
            
            TextInput::make('name_ar')
                ->label('Name (Arabic)')
                ->required(),
            
            Toggle::make('status')
                ->label('Status')
                ->required(),
           
            ])
            ->columns(2);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label(__('course_categories.name'))
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        return $state[app()->getLocale()] ?? $state['en'] ?? '-';
                    }
            
                    if (is_string($state)) {
                        return $state; 
                    }
            
                    return '-';
                })
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
