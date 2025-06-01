<?php

namespace App\Filament\Resources\CourseCategoryResource\Pages;

use Filament\Actions; 
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CourseCategoryResource;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class EditCourseCategory extends EditRecord
{
    protected static string $resource = CourseCategoryResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['name_en'] = $this->record->name['en'] ?? '';
        $data['name_ar'] = $this->record->name['ar'] ?? '';
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = [
            'en' => $data['name_en'],
            'ar' => $data['name_ar'],
        ];
        $data['slug'] = Str::slug($data['name_en']);

        unset($data['name_en'], $data['name_ar']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}