<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="deactivateModal" style="display: none;"></div>

<div class="fixed inset-0 z-10 overflow-y-auto" id="deactivateModalContent" style="display: none;">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                        Desativar Setor
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Por favor, informe o motivo da desativação do setor.
                        </p>
                    </div>
                </div>
            </div>
            <form id="deactivateForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mt-5">
                    <textarea
                        name="deactivation_reason"
                        rows="4"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="Digite o motivo da desativação..."
                        required
                    ></textarea>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button
                        type="submit"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 sm:col-start-2"
                    >
                        Desativar
                    </button>
                    <button
                        type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
                        onclick="closeDeactivateModal()"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showDeactivateModal(sectorId) {
        document.getElementById('deactivateModal').style.display = 'block';
        document.getElementById('deactivateModalContent').style.display = 'block';
        document.getElementById('deactivateForm').action = `/sectors/${sectorId}`;
    }

    function closeDeactivateModal() {
        document.getElementById('deactivateModal').style.display = 'none';
        document.getElementById('deactivateModalContent').style.display = 'none';
    }
</script> 