<div
    draggable="true"
    @dragstart="dragRow($event, {{ $id }})"
    @drop.prevent="dropCard($event, {{ $id }}); dropRow($event, {{ $position }})"
    @dragover.prevent="allowDrop($event)"
    class="bg-white dark:bg-gray-800 rounded-lg shadow lg:flex-shrink-0 border-solid border-2 border-gray-200"
    style="min-width: 18rem; min-height: 35rem;"
>
    <div x-data="{ editingRow: false, newTitle: '{!! $title !!}' }"
        class="p-4 flex items-center justify-between">
        <h3 x-show="!editingRow" @click="editingRow = true" class="text-lg font-semibold text-gray-900 dark:text-white">
            {!! $title !!}
        </h3>
        <input x-model="newTitle" x-show="editingRow"
            @keydown.enter="if (newTitle.trim() === '') { newTitle = '{!! $title !!}'; editingRow = false; } else { $wire.updateRowTitle(newTitle); editingRow = false; }"
            @click.away="if (newTitle.trim() === '') { newTitle = '{!! $title !!}'; editingRow = false; } else { $wire.updateRowTitle(newTitle); editingRow = false; }"
            class="text-sm font-semibold" type="text" />
        <span
            class="flex items-center justify-center w-5 ml-2 text-sm font-semibold text-indigo-500 bg-white rounded bg-opacity-30">{{ count($cards) }}</span>
        <button wire:click="addCard()"
            class="flex items-center justify-center w-6 h-6 ml-auto text-indigo-500 rounded hover:bg-indigo-500 hover:text-indigo-100">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
        </button>
        <div class="relative" x-data="{ openRowOptions: false }">
            <button @click="openRowOptions = !openRowOptions"
                class="flex items-center justify-center w-6 h-6 ml-auto text-indigo-500 focus:outline-none hover:bg-indigo-500 hover:text-indigo-100">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
            </button>

            <div x-show="openRowOptions" @click.away="openRowOptions = false"
                class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                <a href="#" @click="openRowOptions = false; $wire.deleteCards()"
                    class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Empty</a>
                <a href="#" @click="openRowOptions = false; $wire.deleteRow()"
                    class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Remove</a>
            </div>
        </div>
    </div>

    <div class="overflow-auto" style="max-height: 35rem">
        @foreach ($cards as $card)
            @livewire('card-component', ['card' => $card], key($card['id']))
        @endforeach
    </div>
</div>

@script
<script>
    const prefix = `boardway_database_private-row`;
    const id = '{{ $id }}';

    io.on('*', (message) => {
        const channel = message;

        if (channel.startsWith(prefix)) {
            const rowId = channel.split('.')[1];

            if (rowId !== id) {
                return;
            }

            $wire.dispatch('refresh-row', {
                id: rowId
            });
        }
    });
</script>
@endscript
