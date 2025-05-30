<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BaseResource;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
class InstructorResource extends BaseResource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->required()
                    ->email()
                    ->unique('instructors', 'email', ignoreRecord: true)
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'يرجى إدخال البريد الإلكتروني',
                        'email' => 'تنسيق البريد الإلكتروني غير صالح',
                        'unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
                    ]),
                
                Forms\Components\TextInput::make('bio')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('photo')
                ->label('photo')
                ->storeFiles()
                ->directory('instructors')
                ->image()
                ->imagePreviewHeight('100')
                ->maxSize(2048),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bio')
                    ->searchable(),
                    ImageColumn::make('photo')
                    ->label('الصورة')
                    ->disk('public') 
                    ->height(60)
                    ->width(60)
                    ->circular(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
        return [
           
        ];
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
