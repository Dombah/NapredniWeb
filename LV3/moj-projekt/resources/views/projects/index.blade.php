
@extends('layouts.app') {{-- change if your main layout is different --}}

@section('content')
    <h1>Moji projekti</h1>

    <p><a href="{{ route('projects.create') }}">Novi projekt</a></p>

    @if($projects->isEmpty())
        <p>Nema projekata.</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Naziv</th>
                    <th>Voditelj</th>
                    <th>Datum početka</th>
                    <th>Datum završetka</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->leader->name }}</td>
                        <td>{{ $project->start_date }}</td>
                        <td>{{ $project->end_date }}</td>
                        <td>
                            <a href="{{ route('projects.edit', $project) }}">Uredi</a>

                            @if(auth()->id() === $project->leader_id)
                                <form action="{{ route('projects.destroy', $project) }}"
                                      method="POST"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Obrisati projekt?')">
                                        Obriši
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection