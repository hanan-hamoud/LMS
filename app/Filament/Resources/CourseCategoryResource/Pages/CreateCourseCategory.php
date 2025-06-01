<?php

namespace App\Filament\Resources\CourseCategoryResource\Pages;

use App\Filament\Resources\CourseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
class CreateCourseCategory extends CreateRecord
{
    protected static string $resource = CourseCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = [
            'en' => $data['name_en'],
            'ar' => $data['name_ar'],
        ];
        $data['slug'] = Str::slug($data['name_en']);

        unset($data['name_en'], $data['name_ar']);

        return $data;
    }
}