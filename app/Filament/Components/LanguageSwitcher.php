<?php

namespace App\Filament\Components;

use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\View\Panels\Concerns\CanRenderComponents;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public function switchTo($locale)
    {
        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect(request()->header('Referer') ?? '/admin');
    }

    public function render(): View
    {
        return view('components.language-switcher');
    }
}
