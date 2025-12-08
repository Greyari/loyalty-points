@props([
'id' => 'modal_' . uniqid(),
'title' => 'Modal Title',
'size' => 'md', // sm, md, lg, xl, full
'showFooter' => true,
'showSubmit' => true,
'submitText' => 'Submit',
'cancelText' => 'Cancel',
'submitButtonClass' => 'bg-blue-600 hover:bg-blue-700 text-white',
'onSubmit' => null,
])

@php
$sizeClasses = [
'sm' => 'max-w-sm',
'md' => 'max-w-md',
'lg' => 'max-w-2xl',
'xl' => 'max-w-4xl',
'full' => 'max-w-full mx-4',
];
$modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div id="{{ $id }}"
    class="modal fixed inset-0 bg-black/50 bg-opacity-50 hidden items-center justify-center z-50 "
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title">

    <div class="modal-content bg-white rounded-2xl shadow-2xl {{ $modalSize }} w-full transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="modal-header flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="{{ $id }}-title" class="text-xl font-semibold text-gray-800 font-poppins">
                {{ $title }}
            </h3>
            <button type="button"
                class="modal-close text-gray-400 hover:text-gray-600 transition-colors"
                aria-label="Close modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="modal-body p-6 overflow-y-auto flex-1">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if($showFooter)
        <div class="modal-footer flex justify-end gap-3 p-6 border-t border-gray-200">
            <button type="button"
                class="modal-cancel px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-poppins">
                {{ $cancelText }}
            </button>
            @if($showSubmit)
            <button type="button"
                class="modal-submit px-4 py-2 rounded-lg transition-colors font-poppins {{ $submitButtonClass }}">
                {{ $submitText }}
            </button>
            @endif
        </div>
        @endif

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $id }}');
        if (!modal) return;

        const modalContent = modal.querySelector('.modal-content');
        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.modal-cancel');
        const submitBtn = modal.querySelector('.modal-submit');

        // Close modal function
        function closeModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        // Open modal function
        window['open_{{ $id }}'] = function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        // Close modal function (global)
        window['close_{{ $id }}'] = closeModal;

        // Close button
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        // Cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        // Submit button
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                const event = new CustomEvent('modal:submit', {
                    bubbles: true,
                    detail: {
                        modalId: '{{ $id }}'
                    }
                });
                modal.dispatchEvent(event);
            });
        }

        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>