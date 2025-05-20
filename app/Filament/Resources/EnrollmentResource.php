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
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

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
                    ->preload()
                    ->rules([
                        function () {
                            return Rule::unique('enrollments', 'user_id')
                                ->where('course_id', request('course_id'))
                                ->ignore(request()->route('record'));
                        }
                    ]),
                    
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->rules([
                        function () {
                            return Rule::unique('enrollments', 'course_id')
                                ->where('user_id', request('user_id'))
                                ->ignore(request()->route('record'));
                        }
                    ]),
                    
                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->default(now()),
                    
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(true),
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

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = Enrollment::where('user_id', $data['user_id'])
            ->where('course_id', $data['course_id'])
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('خطأ في التسجيل')
                ->body('هذا المستخدم مسجل بالفعل في هذا المقرر.')
                ->danger()
                ->send();
                
            throw ValidationException::withMessages([
                'user_id' => 'هذا المستخدم مسجل بالفعل في هذا المقرر',
                'course_id' => 'هذا المقرر مسجل بالفعل لهذا المستخدم',
            ]);
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $recordId = request()->route('record');

        $exists = Enrollment::where('user_id', $data['user_id'])
            ->where('course_id', $data['course_id'])
            ->when($recordId, fn ($query) => $query->where('id', '!=', $recordId))
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('خطأ في التحديث')
                ->body('هذا المستخدم مسجل بالفعل في هذا المقرر.')
                ->danger()
                ->send();
                
            throw ValidationException::withMessages([
                'user_id' => 'هذا المستخدم مسجل بالفعل في هذا المقرر',
                'course_id' => 'هذا المقرر مسجل بالفعل لهذا المستخدم',
            ]);
        }

        return $data;
    }
}