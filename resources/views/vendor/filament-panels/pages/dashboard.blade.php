<x-filament-panels::page class="fi-dashboard-page">

    {{-- زر تغيير اللغة --}}
    <div class="mb-4">
        <form method="POST" action="{{ route('filament.language.toggle') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}
            </button>
        </form>
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
