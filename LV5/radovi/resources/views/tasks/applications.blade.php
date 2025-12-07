<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tasks.My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($tasks->isEmpty())
                        <p class="text-gray-600">{{ __('tasks.No tasks') }}</p>
                    @else
                        @foreach ($tasks as $task)
                            <div class="mb-8 p-4 border border-gray-200 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">
                                    {{ app()->getLocale() == 'hr' ? $task->naziv_rada : $task->naziv_rada_eng }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">{{ __('tasks.Study Type') }}: {{ $task->tip_studija }}</p>

                                @if ($task->applications->isEmpty())
                                    <p class="text-gray-500">{{ __('tasks.No applications yet') }}</p>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('tasks.Priority') }}
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('tasks.Student') }}
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('tasks.Email') }}
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('tasks.Status') }}
                                                    </th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('tasks.Actions') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($task->applications as $application)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                @if($application->priority == 1) bg-blue-100 text-blue-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ $application->priority }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ $application->user->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ $application->user->email }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                @if($application->status == 'approved') bg-green-100 text-green-800
                                                                @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                                                @else bg-yellow-100 text-yellow-800
                                                                @endif">
                                                                {{ __('tasks.' . ucfirst($application->status)) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            @if($application->status == 'pending' && $application->priority == 1)
                                                                <form action="{{ route('applications.accept', $application) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded">
                                                                        {{ __('tasks.Accept') }}
                                                                    </button>
                                                                </form>
                                                            @elseif($application->priority != 1)
                                                                <span class="text-gray-500 text-xs">{{ __('tasks.Only priority 1 can be accepted') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
