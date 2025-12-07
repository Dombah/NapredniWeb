<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("tasks.Edit Task") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')

                        <!-- Naziv rada -->
                        <div class="mb-4">
                            <label for="naziv_rada" class="block font-medium text-sm text-gray-700">
                                {{ __("tasks.Task Title") }} <span class="text-red-500">*</span>
                            </label>
                            <input id="naziv_rada" type="text" name="naziv_rada" value="{{ old('naziv_rada', $task->naziv_rada) }}" required autofocus
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @error('naziv_rada')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Naziv rada na engleskom -->
                        <div class="mb-4">
                            <label for="naziv_rada_eng" class="block font-medium text-sm text-gray-700">
                                {{ __("tasks.Task Title (English)") }} <span class="text-red-500">*</span>
                            </label>
                            <input id="naziv_rada_eng" type="text" name="naziv_rada_eng" value="{{ old('naziv_rada_eng', $task->naziv_rada_eng) }}" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @error('naziv_rada_eng')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tip studija -->
                        <div class="mb-4">
                            <label for="tip_studija" class="block font-medium text-sm text-gray-700">
                                {{ __("tasks.Study Type") }} <span class="text-red-500">*</span>
                            </label>
                            <select id="tip_studija" name="tip_studija" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __("tasks.Select Study Type") }}</option>
                                <option value="stručni" {{ old('tip_studija', $task->tip_studija) === 'stručni' ? 'selected' : '' }}>{{ __("tasks.Professional") }}</option>
                                <option value="preddiplomski" {{ old('tip_studija', $task->tip_studija) === 'preddiplomski' ? 'selected' : '' }}>{{ __("tasks.Undergraduate") }}</option>
                                <option value="diplomski" {{ old('tip_studija', $task->tip_studija) === 'diplomski' ? 'selected' : '' }}>{{ __("tasks.Graduate") }}</option>
                            </select>
                            @error('tip_studija')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Zadatak rada -->
                        <div class="mb-4">
                            <label for="zadatak_rada" class="block font-medium text-sm text-gray-700">
                                {{ __("tasks.Task Description") }} <span class="text-red-500">*</span>
                            </label>
                            <textarea id="zadatak_rada" name="zadatak_rada" rows="8" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('zadatak_rada', $task->zadatak_rada) }}</textarea>
                            @error('zadatak_rada')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __("tasks.Cancel") }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __("tasks.Update Task") }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
