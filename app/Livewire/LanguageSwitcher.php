<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Component
{
    public string $locale;

    public function mount()
    {
        $this->locale = App::getLocale();
    }

    public function switchLanguage($locale)
    {
        $this->locale = $locale;
        App::setLocale($locale);
        Session::put('locale', $locale);

        return redirect()->to(url()->previous());
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
