<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\CourseCategory;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Filament\Pages\Page;

class ControlPanel extends Page
{


    protected static string $view = 'filament.pages.ControlPanel';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'control panel';
    protected static ?string $title = 'control panel';

    public static function getNavigationLabel(): string
    {
        return __('statistics.name');
    }

   
    public $userCount;
    public $verifiedUsersCount;
    public $courseCount;
    public $activeCourseCount;
    public $lessonCount;
    public $enrollmentCount;
    public $activeEnrollmentCount;
    public $categoryCount;
    public $instructorCount;
    public $activeInstructorCount;
    public $popularCourses; 

    public function mount(): void
    {
      
        $this->userCount = User::count();
        $this->verifiedUsersCount = User::whereNotNull('email_verified_at')->count();

      
        $this->courseCount = Course::count();
        $this->activeCourseCount = Course::where('status', true)->count();

     
        $this->lessonCount = Lesson::count();

  
        $this->enrollmentCount = Enrollment::count();
        $this->activeEnrollmentCount = Enrollment::where('status', true)->count();

      
        $this->categoryCount = CourseCategory::count();
        $this->instructorCount = Instructor::count();
        $this->activeInstructorCount = Instructor::where('status', true)->count();

       
        $this->popularCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();
    }
}
