<?php

namespace App\Filament\Pages;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Filament\Pages\Page;

class ControlPanel extends Page
{
    protected static string $view = 'filament.pages.ControlPanel';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'control panel';
    protected static ?string $title = 'control panel ';

    public $courseCount;
    public $lessonCount;
    public $enrollmentCount;

    public function mount(): void
    {
        $this->courseCount = Course::count();
        $this->lessonCount = Lesson::count();
        $this->enrollmentCount = Enrollment::count();
    }
}
