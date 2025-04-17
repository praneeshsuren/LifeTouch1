// payment
const yearSelect = document.getElementById('year');
const monthSelect = document.getElementById('month');

const months = ['January', 'February', 'March', 'April', 
    'May', 'June', 'July', 'August', 'September', 'October',
    'November', 'December'];
function cardspace(){
    const cardInput = document.getElementById('card-number');
    if (!cardInput) {
        console.error("Element with ID 'card-number' not found.");
        return;
    }
    cardInput.addEventListener('input', (e) =>{
        let cardValue = e.target.value.replace(/\s+/g, '');// Remove existing spaces
        if(cardValue.length > 16) cardValue = cardValue.slice(0, 16);
        const formatedCardNumber = cardValue.match(/.{1,4}/g)?.join(' ') || cardValue; // Add spaces
        e.target.value = formatedCardNumber;
    });
}

function populateMonths(){
    months.forEach((month,index) =>{
        const option = document.createElement('option');
        option.value = index + 1;
        option.textContent = month;
        monthSelect.appendChild(option);
    });
}

function populateYears(){
    const currentYear = new Date().getFullYear();
    for(let i = -10;i <=30; i++){
        const option = document.createElement('option');
        option.value = currentYear + i;
        option.textContent = currentYear + i;
        yearSelect.appendChild(option);
    }
}
document.addEventListener("DOMContentLoaded", () => {
    // Add event listeners and populate the selects after DOM is fully loaded
    cardspace();
    populateMonths();
    populateYears();

    const paymentForm = document.querySelector('.payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const cardNumber = document.getElementById('card-number').value.replace(/\s+/g, ''); // Remove spaces for validation
            const cvv = document.getElementById('cvv').value;

            // Validate CVV (should be 3 digits)
            if (!/^\d{3}$/.test(cvv)) {
                alert('Invalid CVV');
                return;
            }

            // Validate card number (should be 16 digits with spaces after every 4 digits)
            if (!/^\d{4} \d{4} \d{4} \d{4}$/.test(cardNumber)) {
                alert('Invalid Card Number. Please enter a valid 16-digit card number with spaces after every 4 digits.');
                return;
            }

            // If everything is valid, allow form submission
            alert('Form is valid! Submitting...');
            this.submit();
        });
    } else {
        console.error("Form element with id 'paymentForm' not found.");
    }
});
    

