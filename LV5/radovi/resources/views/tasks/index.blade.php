<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __("tasks.Final and Graduation Theses") }}
            </h2>
            @if(Auth::user()->isNastavnik())
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __("tasks.Add New Task") }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($tasks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("tasks.Task Name") }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("tasks.Study Type") }}
                                        </th>
                                        @if(!Auth::user()->isNastavnik())
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __("tasks.Teacher") }}
                                            </th>
                                        @endif
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("tasks.Created Date") }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("tasks.Actions") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $task->naziv_rada }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $task->naziv_rada_eng }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tipColors[$task->tip_studija] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $tipTranslations[$task->tip_studija] ?? ucfirst($task->tip_studija) }}
                                                </span>
                                            </td>
                                            @if(!Auth::user()->isNastavnik())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $task->user->name }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $task->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    {{ __("tasks.View") }}
                                                </a>
                                                @if(Auth::user()->id === $task->user_id)
                                                    <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                        {{ __("tasks.Edit") }}
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('{{ __("tasks.Are you sure you want to delete this task?") }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            {{ __("tasks.Delete") }}
                                                        </button>
                                                    </form>
                                                @elseif(Auth::user()->isStudent())
                                                    @if($task->hasApplied(Auth::id()))
                                                        @php
                                                            $application = $task->getApplicationFor(Auth::id());
                                                        @endphp
                                                        <span class="text-green-600 mr-3">{{ __("tasks.Applied") }} ({{ __("tasks.Priority") }}: {{ $application->priority }})</span>
                                                        <form action="{{ route('tasks.cancelApplication', $task) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                {{ __("tasks.Cancel Application") }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        @php
                                                            $currentApplicationsCount = \App\Models\TaskApplication::where('user_id', Auth::id())->count();
                                                        @endphp
                                                        @if($currentApplicationsCount < 5)
                                                            <button onclick="showPriorityModal({{ $task->id }})" class="text-green-600 hover:text-green-900">
                                                                {{ __("tasks.Apply") }}
                                                            </button>
                                                        @else
                                                            <span class="text-gray-500">{{ __("tasks.Maximum applications reached") }}</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">{{ __("tasks.No tasks added") }}</p>
                            @if(Auth::user()->isNastavnik())
                                <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __("tasks.Add First Task") }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Modal -->
    <div id="priorityModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __("tasks.Select Priority") }}</h3>
                <form id="applyForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __("tasks.Priority") }} (1-5)
                        </label>
                        <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">{{ __("tasks.Choose priority") }}</option>
                            @php
                                $usedPriorities = \App\Models\TaskApplication::where('user_id', Auth::id())->pluck('priority')->toArray();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ in_array($i, $usedPriorities) ? 'disabled' : '' }}>
                                    {{ $i }} {{ in_array($i, $usedPriorities) ? '(' . __("tasks.Used") . ')' : '' }}
                                </option>
                            @endfor
                        </select>
                        <p class="mt-2 text-sm text-gray-500">{{ __("tasks.Priority explanation") }}</p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePriorityModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            {{ __("tasks.Cancel") }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            {{ __("tasks.Apply") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showPriorityModal(taskId) {
            const modal = document.getElementById('priorityModal');
            const form = document.getElementById('applyForm');
            form.action = `/tasks/${taskId}/apply`;
            modal.classList.remove('hidden');
        }

        function closePriorityModal() {
            const modal = document.getElementById('priorityModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('priorityModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePriorityModal();
            }
        });
    </script>
</x-app-layout>
