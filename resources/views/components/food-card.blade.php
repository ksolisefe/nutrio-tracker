@props(['food'])

<div class="bg-slate-800/50 rounded-lg border border-white/10 shadow-md p-4 justify-between flex flex-col gap-2">
    <div class="flex gap-2 flex-wrap">
        @foreach(explode(',', $food->category) as $cat)
            <flux:badge size="sm" title="Category">{{ strlen(trim($cat)) > 25 ? substr(trim($cat), 0, 25) : trim($cat) }}</flux:badge>
        @endforeach
    </div>
    <div class="flex-1">
        <h3 class="text-lg font-medium text-white break-words mb-2 leading-tight">
            {{ strlen($food->name) > 50 ? substr($food->name, 0, 50) . '...' : $food->name }}
        </h3>
        <p class="text-sm text-white break-words mb-0 leading-tight">
            {{ strlen($food->ingredients) > 25 ? substr($food->ingredients, 0, 25) : $food->ingredients }}
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <flux:badge size="sm" color="green" title="Calories">Cal: {{ $food->calories }}</flux:badge>
        <flux:badge size="sm" color="green" title="Protein">P: {{ $food->protein }}</flux:badge>
        <flux:badge size="sm" color="green" title="Carbohydrates">C: {{ $food->carbohydrates }}</flux:badge>
        <flux:badge size="sm" color="blue" title="Fat">F: {{ $food->fat }}</flux:badge>
    </div>
</div>