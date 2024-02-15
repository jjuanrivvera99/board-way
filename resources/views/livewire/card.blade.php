<div
    @click.away="if (!hasErrors) editingCard = false"
    x-data="{ openCardOptions: false, editingCard: false, hasErrors: false, title: '{{ $title }}', description: '{{ $description }}' }"
    class="card"
>
    <div
        draggable="true"
        @dragstart="dragCard($event, {{ $id }})"
        @drop.prevent="dropCard($event, {{ $row_id }}, {{ $position}})"
        @dragover.prevent="allowDrop($event)"
        class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 m-2 rounded border-solid border-2 border-gray-200"
        x-show="!editingCard"
    >
        <button wire:click="removeCard({{ $id }})"
            class="flex items-center justify-center w-6 h-6 ml-auto text-indigo-500 focus:outline-none"
            style="margin-bottom: -1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </button>
        <div @click="if (!hasErrors) editingCard = true">
            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $title }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</p>
        </div>
    </div>

    <div
        x-show="editingCard"
        class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 m-2 rounded border-solid border-2 border-gray-200"
        :class="hasErrors ? 'border-red-500 border-solid border-2' : ''"
    >
        <form
            @card-saved.window="editingCard = false; hasErrors = false"
            @validation-failed.window="hasErrors = true"
            wire:submit="save"
        >
            <input wire:model="title" type="text" class="w-full mb-2" placeholder="Title">
            <div class="h-32 pb-2">
                <textarea wire:model="description" class="w-full h-full resize-none" placeholder="Description"></textarea>
            </div>

            <div class="items-center justify-between">
                <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700" type="submit">Save</button>
                <button @click="editingCard = false"
                    class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700">Cancel</button>
            </div>
        </form>
    </div>
</div>
