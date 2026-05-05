<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            {{ __("You're logged in!") }}
            <div class="mt-3">
                <a href="{{ route('portfolio.edit') }}" class="btn btn-primary">Go to Portfolio Manager</a>
            </div>
        </div>
    </div>
</x-app-layout>
