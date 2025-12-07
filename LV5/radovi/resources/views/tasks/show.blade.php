<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("tasks.Task Details") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __("tasks.Back to Task List") }}
                        </a>
                    </div>

                    <div class="space-y-6">
                        <!-- Naziv rada -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Task Title") }}</h3>
                            <p class="text-gray-700">{{ $task->naziv_rada }}</p>
                        </div>

                        <!-- Naziv rada na engleskom -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Task Title (English)") }}</h3>
                            <p class="text-gray-700">{{ $task->naziv_rada_eng }}</p>
                        </div>

                        <!-- Tip studija -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Study Type") }}</h3>
                            @php
                                $tipColors = [
                                    'stručni' => 'bg-blue-100 text-blue-800',
                                    'preddiplomski' => 'bg-yellow-100 text-yellow-800',
                                    'diplomski' => 'bg-purple-100 text-purple-800',
                                ];
                                $tipTranslations = [
                                    'stručni' => __("tasks.Professional"),
                                    'preddiplomski' => __("tasks.Undergraduate"),
                                    'diplomski' => __("tasks.Graduate"),
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $tipColors[$task->tip_studija] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $tipTranslations[$task->tip_studija] ?? ucfirst($task->tip_studija) }}
                            </span>
                        </div>

                        <!-- Zadatak rada -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Task Description") }}</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-line">{{ $task->zadatak_rada }}</p>
                            </div>
                        </div>

                        <!-- Nastavnik -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Teacher") }}</h3>
                            <p class="text-gray-700">{{ $task->user->name }}</p>
                        </div>

                        <!-- Datum kreiranja -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Created Date") }}</h3>
                            <p class="text-gray-700">{{ $task->created_at->format('d.m.Y H:i') }}</p>
                        </div>

                        @if($task->created_at != $task->updated_at)
                            <!-- Datum posljednje izmjene -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __("tasks.Last Modified") }}</h3>
                                <p class="text-gray-700">{{ $task->updated_at->format('d.m.Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    @if(Auth::user()->id === $task->user_id)
                        <div class="mt-8 flex items-center space-x-4">
                            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __("tasks.Edit") }}
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('{{ __("tasks.Are you sure you want to delete this task?") }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __("tasks.Delete") }}
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::user()->isStudent())
                        <div class="mt-8">
                            @if($task->hasApplied(Auth::id()))
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                        <span class="font-semibold">{{ __("tasks.Applied") }}</span> - {{ __("tasks.You have applied to this task") }}
                                    </div>
                                    <form action="{{ route('tasks.cancelApplication', $task) }}" method="POST" onsubmit="return confirm('{{ __("tasks.Are you sure you want to cancel your application?") }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __("tasks.Cancel Application") }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('tasks.apply', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __("tasks.Apply to Task") }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
