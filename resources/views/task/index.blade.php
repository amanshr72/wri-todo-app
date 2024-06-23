<x-app-layout>
    {{-- <x-slot name="header"></x-slot> --}}

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <form id="taskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <div class="flex items-center">
                                <div class="relative w-full">
                                    <input type="text" id="title" name="title"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-5 p-2"
                                        placeholder="Type Task Name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="submit" id="addTaskBtn"
                                class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Add Task
                            </button>
                            <button type="button" id="showAllTaskBtn"
                                class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                View All Task
                            </button>
                        </div>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Task Name</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Mark Progress</th>
                                <th scope="col" class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <section >
                            <tbody id="taskList">
                                @include('task.task-list')
                            </tbody>
                        </section>
                    </table>
                </div>

            </div>
        </div>
    </section>


    <script>
        $(document).ready(function() {
            function initializeModals() {
                $('[data-modal-toggle]').each(function() {
                    const targetId = $(this).data('modal-toggle');
                    const $targetEl = document.getElementById(targetId);
                    if ($targetEl) {
                        const modal = new Modal($targetEl);
                        $(this).off('click').on('click', function() {
                            modal.show();
                        });
                        $($targetEl).find('[data-modal-close]').off('click').on('click', function() {
                            modal.hide();
                        });
                    }
                });
            }

            function reattachEventListeners() {
                $('button[data-modal-toggle]').off('click').on('click', function() {
                    const targetId = $(this).data('modal-toggle');
                    const $targetEl = document.getElementById(targetId);
                    if ($targetEl) {
                        const modal = new Modal($targetEl);
                        modal.show();
                    }
                });
            }

            function handleFormSubmission(form, successCallback) {
                var formData = form.serialize();
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(resp) {
                        $('#taskList').empty().html(resp);
                        successCallback();
                        initializeModals();
                        reattachEventListeners();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            $('#taskForm').on('submit', function(event) {
                event.preventDefault();
                handleFormSubmission($(this), function() {
                    $('#title').val('');
                });
            });

            $(document).on('submit', '#taskUpdateForm', function(event) {
                event.preventDefault();
                var form = $(this);
                var taskId = form.data('id');
                const $targetEl = document.getElementById('updateTask-' + taskId);
                const modal = new Modal($targetEl);
                handleFormSubmission(form, function() {
                    modal.hide();
                });
            });

            $(document).on('submit', '#taskDeleteForm', function(event) {
                event.preventDefault();
                var form = $(this);
                var taskId = form.data('id');
                const $targetEl = document.getElementById('deleteModal-' + taskId);
                const modal = new Modal($targetEl);
                handleFormSubmission(form, function() {
                    modal.hide();
                });
            });
            
            $(document).on('change', '.mark_progress', function(event) {
                var checkbox = $(this);
                var taskId = checkbox.data('id');
                var isChecked = checkbox.prop('checked') ? 1 : 0;
                $.ajax({
                    url: "{{route('markProgress')}}",
                    type: 'GET',
                    data: {_token: '{{ csrf_token() }}', status: isChecked, id: taskId},
                    success: function(resp){
                        $('#taskList').empty();
                        $('#taskList').html(resp);
                        initializeModals();
                        reattachEventListeners();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });

            $('#showAllTaskBtn').on('click', function(event){
                $.ajax({
                    url: "{{route('showAllTask')}}",
                    type: 'GET',
                    success: function(resp){
                        $('#taskList').empty();
                        $('#taskList').html(resp);
                        initializeModals();
                        reattachEventListeners();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });

            initializeModals();
            reattachEventListeners();
        });
    </script>

</x-app-layout>
