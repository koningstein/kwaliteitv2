@props(['title', 'description'])

<div class="flex w-full flex-col gap-1">
    <h1 class="text-xl font-bold text-slate-900">{{ $title }}</h1>
    <p class="text-sm text-slate-500">{{ $description }}</p>
</div>
