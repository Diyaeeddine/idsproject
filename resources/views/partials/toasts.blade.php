<!-- Container pour toutes les notifications -->
<div id="toast-container" class="fixed top-5 right-5 z-50 space-y-4 pointer-events-none">
  
    <div id="toast-new"
         class="hidden pointer-events-auto flex items-center w-full max-w-xs p-4 text-blue-700 bg-blue-100 rounded-lg shadow-md dark:text-blue-400 dark:bg-blue-900 transform transition-all duration-300 ease-in-out"
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-600 bg-blue-200 rounded-lg dark:bg-blue-800 dark:text-blue-200">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="sr-only">Info icon</span>
        </div>
        <div id="toast-new-message" class="ms-3 text-sm font-normal flex-1"></div>
        <button type="button" id="toast-new-close-btn"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-blue-400 hover:text-blue-900 rounded-lg focus:ring-2 focus:ring-blue-300 p-1.5 hover:bg-blue-100 inline-flex items-center justify-center h-8 w-8 dark:text-blue-500 dark:hover:text-white dark:bg-blue-800 dark:hover:bg-blue-700 transition-colors duration-200"
                aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

    <!-- Toast pour demandes en retard -->
    <div id="toast-late"
         class="hidden pointer-events-auto flex items-center w-full max-w-xs p-4 text-red-700 bg-red-100 rounded-lg shadow-md dark:text-red-400 dark:bg-red-900 transform transition-all duration-300 ease-in-out"
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-600 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
            </svg>
            <span class="sr-only">Warning icon</span>
        </div>
        <div id="toast-late-message" class="ms-3 text-sm font-normal flex-1"></div>
        <button type="button" id="toast-late-close-btn"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-red-400 hover:text-red-900 rounded-lg focus:ring-2 focus:ring-red-300 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:text-red-500 dark:hover:text-white dark:bg-red-800 dark:hover:bg-red-700 transition-colors duration-200"
                aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

    <!-- Toast pour succès -->
    <div id="toast-success"
         class="hidden pointer-events-auto flex items-center w-full max-w-xs p-4 text-green-700 bg-green-100 rounded-lg shadow-md dark:text-green-400 dark:bg-green-900 transform transition-all duration-300 ease-in-out"
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-600 bg-green-200 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 10 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <span class="sr-only">Success icon</span>
        </div>
        <div id="toast-success-message" class="ms-3 text-sm font-normal flex-1"></div>
        <button type="button" id="toast-success-close-btn"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-green-400 hover:text-green-900 rounded-lg focus:ring-2 focus:ring-green-300 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8 dark:text-green-500 dark:hover:text-white dark:bg-green-800 dark:hover:bg-green-700 transition-colors duration-200"
                aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

    <!-- Toast pour erreurs -->
    <div id="toast-error"
         class="hidden pointer-events-auto flex items-center w-full max-w-xs p-4 text-red-700 bg-red-100 rounded-lg shadow-md dark:text-red-400 dark:bg-red-900 transform transition-all duration-300 ease-in-out"
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-600 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <span class="sr-only">Error icon</span>
        </div>
        <div id="toast-error-message" class="ms-3 text-sm font-normal flex-1"></div>
        <button type="button" id="toast-error-close-btn"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-red-400 hover:text-red-900 rounded-lg focus:ring-2 focus:ring-red-300 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:text-red-500 dark:hover:text-white dark:bg-red-800 dark:hover:bg-red-700 transition-colors duration-200"
                aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>

<script>
class ToastManager {
    constructor() {
        this.activeToasts = new Set();
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            // Setup close buttons
            const closeButtons = [
                'toast-new-close-btn',
                'toast-late-close-btn', 
                'toast-success-close-btn',
                'toast-error-close-btn'
            ];

            closeButtons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener('click', () => {
                        const toastId = btnId.replace('-close-btn', '');
                        this.hideToast(toastId);
                    });
                }
            });

            // Test automatique (à supprimer en production)
            setTimeout(() => {
                this.showToast('new', "Vous avez nouvelles demandes à traiter.");
            }, 1000);
            
            setTimeout(() => {
                this.showToast('late', "Certaines demandes n'ont pas été remplies depuis plus d'une minute !");
            }, 2000);
        });
    }

    showToast(type, message, duration = 5000) {
        console.log('Showing toast:', type, message);
        
        const toastElement = document.getElementById(`toast-${type}`);
        const messageElement = document.getElementById(`toast-${type}-message`);
        
        if (!toastElement || !messageElement) {
            console.error(`Toast elements not found for type: ${type}`);
            return;
        }

        // Set message
        messageElement.textContent = message;
        
        // Show with animation
        toastElement.classList.remove('hidden');
        toastElement.classList.add('animate-slide-in-right');
        
        // Add to active toasts
        this.activeToasts.add(type);
        
        // Auto-hide after duration
        if (duration > 0) {
            setTimeout(() => {
                this.hideToast(type);
            }, duration);
        }
    }

    hideToast(type) {
        const toastElement = document.getElementById(`toast-${type}`);
        if (!toastElement) return;

        // Add exit animation
        toastElement.classList.add('animate-slide-out-right');
        
        // Remove from DOM after animation
        setTimeout(() => {
            toastElement.classList.add('hidden');
            toastElement.classList.remove('animate-slide-in-right', 'animate-slide-out-right');
            this.activeToasts.delete(type);
        }, 300);
    }

    hideAll() {
        this.activeToasts.forEach(type => {
            this.hideToast(type);
        });
    }
}

// Initialize toast manager
const toastManager = new ToastManager();

// Global function for easy access
function showToast(type, message, duration = 5000) {
    toastManager.showToast(type, message, duration);
}

function hideToast(type) {
    toastManager.hideToast(type);
}
</script>

<style>
/* Animations personnalisées */
@keyframes slide-in-right {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out-right {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out forwards;
}

.animate-slide-out-right {
    animation: slide-out-right 0.3s ease-in forwards;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #toast-container {
        left: 1rem;
        right: 1rem;
        top: 1rem;
    }
    
    #toast-container > div {
        max-width: none;
    }
}

/* Hover effects */
#toast-container > div:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Z-index pour être au-dessus des modals */
#toast-container {
    z-index: 9999;
}

/* Progress bar pour les toasts avec durée */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    animation: progress-shrink 5s linear forwards;
}

@keyframes progress-shrink {
    from { width: 100%; }
    to { width: 0%; }
}
</style>