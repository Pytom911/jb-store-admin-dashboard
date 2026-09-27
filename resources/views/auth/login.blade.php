@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <x-form.input
            name="email"
            label="Email"
            type="email"
            :value="old('email')"
            required
            autocomplete="username"
            placeholder="admin@akunstore.test"
        />

        <x-form.password
            name="password"
            label="Password"
            required
            autocomplete="current-password"
        />

        <x-button type="submit" class="w-full">Masuk</x-button>
    </form>
@endsection
