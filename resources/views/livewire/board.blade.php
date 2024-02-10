<div>
    <div class="grid grid-cols-1 px-4 pt-6 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="/"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a wire:navigate href="/boards"
                                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-primary-500">Boards</a>
                        </div>
                    </li>
                </ol>
            </nav>
            <div
                x-data="{ editingBoard: false, newBoardTitle: '{!! $title !!}'}"
            >
                <h1 @click="editingBoard = true" x-show="!editingBoard" class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"> {!! $title !!}</h1>
                <input
                    x-model="newBoardTitle" x-show="editingBoard"
                    @keydown.enter="if (newBoardTitle.trim() === '') { newBoardTitle = '{!! $title !!}'; editingBoard = false; } else { $wire.updateBoardTitle(newBoardTitle); editingBoard = false; }"
                    @click.away="if (newBoardTitle.trim() === '') { newBoardTitle = '{!! $title !!}'; editingBoard = false; } else { $wire.updateBoardTitle(newBoardTitle); editingBoard = false; }"
                    class="text-xl font-semibold" type="text"
                />
            </div>
        </div>

        <div x-data="dragDrop()" class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto py-6 px-4 sm:px-6 md:px lg:px-8 ml-0" style="max-width: 100rem">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 md:flex md:overflow-x-auto lg:flex lg:overflow-x-auto gap-4">
                    @foreach ($rows as $row)
                        @livewire('row-component', ['row' => $row], key($row['id']))
                    @endforeach

                    <div wire:click="addRow" class="flex justify-center items-center bg-white dark:bg-gray-800 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" style="min-width: 18rem; min-height: 35rem">
                        <button class="p-4 text-gray-400 opacity-50" aria-label="Add new card">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Echo.private('board.{{ $id }}')
        .listen('BoardUpdated', (e) => {
            console.log('BoardUpdated', e.board.id);
            $wire.dispatch('refresh-board', {
                id: e.board.id
            });
        });
</script>
@endscript

<script>

    /**
     * Drag and drop functionality for the board
     * @returns {Object}
     */
    function dragDrop() {
        return {
            draggingCard: null,
            draggingRow: null,
            dragCard(event, cardId) {
                this.draggingCard = cardId;
                event.dataTransfer.setData('text/plain', cardId);

                // Avoid dragging the row when dragging a card
                event.stopPropagation();
            },
            dragRow(event, rowId) {
                this.draggingRow = rowId;
                event.dataTransfer.setData('text/plain', rowId);
            },
            allowDrop(event) {
                event.preventDefault();
            },
            dropCard(event, rowId, position = 0) {
                event.preventDefault();
                const cardId = this.draggingCard;

                if (cardId) {
                    this.draggingCard = null;
                    this.$dispatch('move-card', {
                        cardId,
                        rowId,
                        position
                    });
                }

            },
            dropRow(event, position) {
                event.preventDefault();

                const rowId = this.draggingRow;
                if (rowId) {
                    this.$dispatch('move-row', { rowId, position });
                    this.draggingRow = null;
                }
            }
        };
    }
</script>
