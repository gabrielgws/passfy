document.addEventListener("livewire:initialized", () => {
    Livewire.on("toast", (event) => {
        const toastContainer = document.createElement("div");
        toastContainer.innerHTML = `
            <div class="fixed top-4 right-4 z-50 max-w-xs bg-white rounded-xl shadow-lg border border-gray-200 p-4 transition-all duration-300 ease-in-out"
                 x-data="{ show: false }"
                 x-init="() => { show = true; setTimeout(() => { show = false; }, 3000) }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full">
                <div class="flex items-center">
                    ${
                        event.type === "success"
                            ? '<svg class="w-6 h-6 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>'
                            : '<svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900">${
                            event.message
                        }</p>
                    </div>
                </div>
            </div>
        `;

        // Adicionar Alpine.js para efeitos de transição
        toastContainer
            .querySelector("div")
            .setAttribute("x-data", "{ show: false }");

        document.body.appendChild(toastContainer);
    });
});
