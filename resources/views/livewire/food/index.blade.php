<div>
    <flux:heading size="xl" level="1">Food Index</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Here's what's new today in the USDA Food Database</flux:text>

    <flux:table :paginate="$foods">
        <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Calories</flux:table.column>
                <flux:table.column>Protein</flux:table.column>
                <flux:table.column>Carbohydrates</flux:table.column>
                <flux:table.column>Fat</flux:table.column>
                <flux:table.column>Serving Size</flux:table.column>
                <flux:table.column>Serving Size Unit</flux:table.column>
                <flux:table.column>Brand Owner</flux:table.column>
                <flux:table.column>Food Type</flux:table.column>
                <flux:table.column>Food Portion</flux:table.column>
                <flux:table.column>Food Portion Size</flux:table.column>
                <flux:table.column>Food Portion Unit</flux:table.column>
        </flux:table.columns>
        <flux:table.rows wire:key="food-rows">
            @foreach ($foods as $food)
                <flux:table.row :key="$food->id">
                    <flux:table.cell title="{{ $food->name }}">{{ substr($food->name, 0, 50) }}...</flux:table.cell>
                    <flux:table.cell title="{{ $food->category }}">{{ substr($food->category, 0, 25) }}...</flux:table.cell>
                    <flux:table.cell>{{ $food->calories }}</flux:table.cell>
                    <flux:table.cell>{{ $food->protein }}</flux:table.cell>
                    <flux:table.cell>{{ $food->carbohydrates }}</flux:table.cell>
                    <flux:table.cell>{{ $food->fat }}</flux:table.cell>
                    <flux:table.cell>{{ $food->serving_size }}</flux:table.cell>
                    <flux:table.cell>{{ $food->serving_size_unit }}</flux:table.cell>
                    <flux:table.cell>{{ $food->brand_owner }}</flux:table.cell>
                    <flux:table.cell>{{ $food->food_type }}</flux:table.cell>
                    <flux:table.cell>{{ $food->food_portion }}</flux:table.cell>
                    <flux:table.cell>{{ $food->food_portion_size }}</flux:table.cell>
                    <flux:table.cell>{{ $food->food_portion_unit }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
