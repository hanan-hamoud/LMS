<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Forms\Get;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('المقرر')
                    ->helperText('لا يمكن تكرار تسجيل نفس المستخدم في نفس المقرر.')
                    ->rules([
                        function (Get $get, ?Enrollment $record) {
                            return function (string $attribute, $value, $fail) use ($get, $record) {
                                $userId = $get('user_id');
                                $courseId = $value;
                                
                                if (!$userId || !$courseId) {
                                    return;
                                }
                                
                                $query = Enrollment::where('user_id', $userId)
                                    ->where('course_id', $courseId);
                                
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }
                                
                                if ($query->exists()) {
                                    $fail('هذا المستخدم مسجل بالفعل في هذا المقرر.');
                                }
                            };
                        },
                    ]),

                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->default(now()),

                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->label('فعال؟'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}