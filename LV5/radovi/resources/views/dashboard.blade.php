<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tasks.Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __("tasks.Welcome!") }}</h3>
                    
                    <div class="mb-4">
                        <p class="text-gray-600">{{ __("tasks.Your role:") }}
                            @php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'nastavnik' => 'bg-blue-100 text-blue-800',
                                    'student' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColors[Auth::user()->role] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __("tasks." . Auth::user()->role) }}
                            </span>
                        </p>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>{{ __("tasks.Admin privileges:") }}</strong> {{ __("tasks.As admin, you can manage user roles through") }}
                                <a href="{{ route('admin.users.index') }}" class="underline hover:text-blue-900">{{ __("tasks.user management panel") }}</a>.
                            </p>
                        </div>
                    @elseif(Auth::user()->isNastavnik())
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                {{ __("tasks.Your role is") }} <strong>{{ __("tasks.teacher") }}</strong>. {{ __("tasks.The admin assigned you this role.") }}
                            </p>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">
                                {{ __("tasks.Your role is") }} <strong>{{ __("tasks.student") }}</strong>. {{ __("tasks.If you need a different role, contact the administrator.") }}
                            </p>
                        </div>
                        
                        @php
                            $approvedApplication = \App\Models\TaskApplication::where('user_id', Auth::id())
                                ->where('status', 'approved')
                                ->with('task.user')
                                ->first();
                        @endphp
                        
                        @if($approvedApplication)
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="text-lg font-semibold text-blue-900 mb-3">{{ __("tasks.Your Approved Task") }}</h4>
                                <div class="bg-white p-4 rounded border border-blue-100">
                                    <h5 class="font-semibold text-gray-900">
                                        {{ app()->getLocale() == 'hr' ? $approvedApplication->task->naziv_rada : $approvedApplication->task->naziv_rada_eng }}
                                    </h5>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>{{ __("tasks.Teacher") }}:</strong> {{ $approvedApplication->task->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>{{ __("tasks.Study Type") }}:</strong> {{ $approvedApplication->task->tip_studija }}
                                    </p>
                                    <a href="{{ route('tasks.show', $approvedApplication->task) }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800 underline">
                                        {{ __("tasks.View Details") }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
