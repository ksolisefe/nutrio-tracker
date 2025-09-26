@extends('layouts.app')

@section('content')
<div>
    <flux:heading size="xl" level="1">Welcome to the USDA Food Database for {{ config('app.name') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Here's what's new today in the USDA Food Database</flux:text>
    <flux:text class="mt-2 mb-6 text-base">Want to check out the data? <a href="{{ route('food.index') }}" class="text-blue-500">Click here</a></flux:text>
</div>
@endsection
