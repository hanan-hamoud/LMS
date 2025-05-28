<x-filament-panels::page class="fi-dashboard-page">

    {{-- زر تغيير اللغة --}}
    <div class="mb-4">
        <form method="POST" action="{{ route('locale.switch') }}">
            @csrf
            <select name="locale" onchange="this.form.submit()">
                <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>

                
            </select>
        </form>
        
    </div>
    <div class="mb-4 flex gap-4">
        <a href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a>
        <a href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a>
    </div>
    
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
        "
        :widgets="$this->getVisibleWidgets()"
    />
</x-filament-panels::page>
