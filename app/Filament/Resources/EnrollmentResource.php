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
use App\Filament\Resources\BaseResource;
class EnrollmentResource extends BaseResource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.Enrollment');
    }

    public static function getModelLabel(): string
    {
        return __('enrollments.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('enrollments.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(__('enrollments.user')),

                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label(__('enrollments.course'))
                    ->helperText(__('enrollments.duplicate_error'))
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
                                    $fail(__('enrollments.duplicate_error'));
                                }
                            };
                        },
                    ]),

                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->default(now())
                    ->label(__('enrollments.enrolled_at')),

                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->label(__('enrollments.status')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('enrollments.user'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('enrollments.course'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->label(__('enrollments.enrolled_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('enrollments.status'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('enrollments.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('enrollments.updated_at'))
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
