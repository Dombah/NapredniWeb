@extends('layouts.app')

@section('content')
    <h1>Novi projekt</h1>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <div>
            <label>Naziv</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Opis</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Cijena</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}">
        </div>

        <div>
            <label>Obavljeni poslovi</label>
            <textarea name="completed_tasks">{{ old('completed_tasks') }}</textarea>
        </div>

        <div>
            <label>Datum početka</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}">
        </div>

        <div>
            <label>Datum završetka</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}">
        </div>

        <div>
            <label>Članovi tima (Ctrl+click za više)</label><br>
            <select name="members[]" multiple size="5">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">Spremi</button>
    </form>
@endsection