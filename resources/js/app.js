// Initialize Livewire
document.addEventListener('DOMContentLoaded', function() {
    // Ensure Livewire is properly initialized
    if (window.Livewire) {
        console.log('Livewire is loaded and initialized');
        
        // Verificar se os eventos do Livewire estão funcionando
        window.Livewire.hook('message.processed', (message, component) => {
            console.log('Livewire message processed:', message);
        });
        
        // Verificar se o evento redirectToStripeCheckout está registrado
        console.log('Livewire events:', window.Livewire);
    } else {
        console.error('Livewire is not loaded');
    }
});