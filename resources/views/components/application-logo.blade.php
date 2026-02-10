{{-- resources/views/vendor/jetstream/components/application-logo.blade.php --}}
<div class="flex items-center space-x-2">
    <img src="{{ asset('images/logo.png') }}" 
         alt="{{ config('app.name') }}"
         class="h-12 w-auto">
    <span class="text-xl font-bold text-green-700 hidden md:inline">
        {{ config('app.name') }}
    </span>
</div>