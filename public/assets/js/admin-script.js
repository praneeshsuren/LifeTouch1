// Select elements
const body = document.querySelector('body');
const menuItems = document.querySelectorAll('.menu ul li a');
const menuButton = document.querySelector('.menu-btn');
const modeSwitch = document.querySelector('.toggle-switch');
const modeText = document.querySelector('.mode-text');

// Function to remove the active class from all items
function removeActiveClass() {
    menuItems.forEach(item => {
        item.parentElement.classList.remove('active');
    });
}

// Add click event listener to each menu item
menuItems.forEach(item => {
    item.addEventListener('click', function () {
        // Remove the active class from all items
        removeActiveClass();

        // Add the active class to the clicked item
        item.parentElement.classList.add('active');
        
        // Toggle sub-menu if it exists
        const subMenu = item.nextElementSibling;
        if (subMenu && subMenu.classList.contains('sub-menu')) {
            subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
        }

        // Toggle arrow active state
        const arrow = item.querySelector('.arrow');
        if (arrow) {
            arrow.classList.toggle('active');
        }
    });
});

// Sidebar toggle functionality
menuButton.addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main');

    sidebar.classList.toggle('active');
    main.classList.toggle('active');
});

// Dark/Light Mode Initialization
function initializeDarkMode() {
    const savedMode = localStorage.getItem('mode');
    
    // Set mode based on saved preference
    if (savedMode === 'dark') {
        body.classList.add('dark');
        if (modeText) modeText.innerText = 'Light Mode';
    } else {
        body.classList.remove('dark');
        if (modeText) modeText.innerText = 'Dark Mode';
    }
}

// Toggle Dark/Light Mode
function toggleDarkMode() {
    body.classList.toggle('dark');
    const isDarkMode = body.classList.contains('dark');
    
    if (modeText) {
        modeText.innerText = isDarkMode ? 'Light Mode' : 'Dark Mode';
    }

    // Save the current mode to localStorage
    localStorage.setItem('mode', isDarkMode ? 'dark' : 'light');
}

// Attach event listener to the mode switch
if (modeSwitch) {
    modeSwitch.addEventListener('click', toggleDarkMode);
}

// Initialize the dark mode on page load
initializeDarkMode();

// const editBtn = document.getElementById('editBtn');
// const deleteBtn = document.getElementById('deleteBtn');
// const saveBtn = document.getElementById('saveBtn');
// const cancelBtn = document.getElementById('cancelBtn');
// const formElements = document.querySelectorAll('#userForm input, #userForm select');
// const userIdInput = document.getElementById('user_id');
// const changePictureBtn = document.getElementById('changePictureBtn');
// const profilePictureInput = document.getElementById('profilePictureInput');


// // Edit button functionality
// editBtn.addEventListener('click', function () {
//     // Enable the input fields for editing, except the user ID
//     formElements.forEach(element => {
//         if (element !== userIdInput) {
//             element.disabled = false;
//         }
//     });

//     // Show "Change Picture" button
//     changePictureBtn.style.display = 'inline-block';

//     // Toggle the buttons' visibility
//     saveBtn.style.display = 'inline-block';
//     cancelBtn.style.display = 'inline-block';
//     editBtn.style.display = 'none';
//     deleteBtn.style.display = 'none';
// });

// // Save button functionality
// saveBtn.addEventListener('click', function () {
//     // Submit the form after making changes
//     document.getElementById('userForm').submit();
// });

// // Cancel button functionality
// cancelBtn.addEventListener('click', function () {
//     // Disable the input fields and revert changes
//     formElements.forEach(element => {
//         if (element !== userIdInput) {
//             element.disabled = true;
//         }
//     });

//     // Hide the "Change Picture" button
//     changePictureBtn.style.display = 'none';

//     // Hide the save and cancel buttons, show the edit and delete buttons
//     saveBtn.style.display = 'none';
//     cancelBtn.style.display = 'none';
//     editBtn.style.display = 'inline-block';
//     deleteBtn.style.display = 'inline-block';
// });

// // Handle "Change Picture" button click
// changePictureBtn.addEventListener('click', function () {
//     profilePictureInput.click(); // Trigger the file input dialog
// });

// // JavaScript for Modal
// const modal = document.getElementById('event-modal');
// const modalTitle = document.getElementById('modal-title');
// const modalDate = document.getElementById('modal-date');
// const modalTime = document.getElementById('modal-time');
// const modalLocation = document.getElementById('modal-location');
// const modalDescription = document.getElementById('modal-description');

// // Show modal function
// function showModal(event) {
//   modalTitle.textContent = event.title || 'No Title';
//   modalDate.textContent = `Date: ${event.date || 'No Date'}`;
//   modalTime.textContent = `Time: ${event.time || 'No Time'}`;
//   modalLocation.textContent = `Location: ${event.location || 'No Location'}`;
//   modalDescription.textContent = event.description || 'No Description';

//   modal.classList.add('active');
// }

// // Close modal function
// function closeModal() {
//   modal.classList.remove('active');
// }