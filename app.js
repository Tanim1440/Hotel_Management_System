// Component 1: Handles the generation of individual property cards
class HotelCard {
    constructor(title, price, image) {
        this.title = title;
        this.price = price;
        this.image = image;
    }

    render() {
        return `
            <div class="bg-white rounded-2xl overflow-hidden state-transition cursor-pointer shadow-sm hover:-translate-y-1 hover:shadow-xl border border-gray-100">
                <img src="${this.image}" alt="${this.title}" class="w-full h-[250px] object-cover">
                <div class="p-5">
                    <h3 class="mb-2 text-xl font-bold text-gray-800">${this.title}</h3>
                    <p class="text-2xl font-bold text-gray-900">${this.price} <span class="text-lg font-normal text-gray-500">/ Night</span></p>
                </div>
            </div>
        `;
    }
}

// Component 2: Handles the UI and validation logic for the registration modal
class RegistrationForm {
    render() {
        return `
            <div id="registration-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm state-transition">
                <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100 w-full max-w-lg relative">
                    
                    <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 text-2xl font-bold">&times;</button>
                    
                    <header class="mb-8 text-center">
                        <h2 class="text-3xl font-extrabold text-[#ff385c]">Join to Book</h2>
                        <p class="text-gray-600 mt-2">Create a secure account to finalize your reservations.</p>
                    </header>
                    
                    <form id="registration-form" novalidate class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="user-email">Email Address</label>
                            <input type="email" id="user-email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff385c] focus:outline-none state-transition"
                                placeholder="e.g., traveler@explore.com">
                            <p id="email-error" class="text-red-500 text-xs mt-1 hidden">Please provide a valid email address.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1" for="user-password">Secure Password</label>
                            <input type="password" id="user-password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff385c] focus:outline-none state-transition"
                                placeholder="Min 8 characters, with number">
                            <div class="h-2 w-full bg-gray-200 rounded-full mt-2 overflow-hidden">
                                <div id="strength-meter" class="h-full w-0 bg-red-500 state-transition"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-gray-500 mt-1">Strength: Empty</p>
                        </div>
                        <button type="submit" class="w-full bg-[#ff385c] hover:bg-[#e03150] text-white font-bold py-3 px-4 rounded-lg shadow state-transition">
                            Create Secure Account
                        </button>
                    </form>
                </div>
            </div>
        `;
    }

        bindEvents() {
        const registrationForm = document.getElementById('registration-form');
        const userEmail = document.getElementById('user-email');
        const emailError = document.getElementById('email-error');
        const userPassword = document.getElementById('user-password');
        const strengthMeter = document.getElementById('strength-meter');
        const strengthText = document.getElementById('strength-text');
        const modal = document.getElementById('registration-modal');
        const closeModalBtn = document.getElementById('close-modal');

        // Close Modal Logic
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Real-Time Email Validation
        userEmail.addEventListener('input', () => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(userEmail.value)) {
                userEmail.classList.remove('border-red-500', 'focus:ring-red-500');
                userEmail.classList.add('border-green-500', 'focus:ring-green-500');
                emailError.classList.add('hidden');
            } else {
                userEmail.classList.remove('border-green-500', 'focus:ring-green-500');
                userEmail.classList.add('border-red-500', 'focus:ring-red-500');
                emailError.classList.remove('hidden');
            }
        });
        // Real-Time Password Complexity Analysis
       

        // Form Submission Interceptor
    }
}