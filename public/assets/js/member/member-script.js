// Select all menu items
document.addEventListener("DOMContentLoaded", function() {
const body = document.querySelector('body');
const menuItems = document.querySelectorAll('.menu ul li a');
const menuButton = document.querySelector('.menu-btn');
const modeSwitch = document.querySelector('.toggle-switch');
const modeText = document.querySelector('.mode-text');
const sidebar = document.querySelector('.sidebar');
const main = document.querySelector('main');
// Function to remove the active class from all items
function removeActiveClass() {
    menuItems.forEach(item => {
        item.parentElement.classList.remove('active');
    });
}

// Add click event listener to each menu item
if(menuItems.length>0){
menuItems.forEach(item => {
    item.addEventListener('click', function() {
        // Remove the active class from all items
        removeActiveClass();

        // Add the active class to the clicked item
        item.parentElement.classList.add('active');
        
        // Toggle sub-menu if it exists
        const subMenu = item.nextElementSibling;
        if (subMenu && subMenu.classList.contains('sub-menu')) {
            subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
        }
        const arrow = item.querySelector('.arrow');
        if (arrow) {
            arrow.classList.toggle('active');
        }
    });
});
}

menuButton.addEventListener('click', function() {
    sidebar.classList.toggle('active');
    main.classList.toggle('active');
});



let mode = localStorage.getItem('mode');
if (mode === 'dark') {
    body.classList.add('dark');
    modeText.innerText = 'Light Mode';
}

// Toggle Dark/Light Mode
modeSwitch.addEventListener('click', () => {
    body.classList.toggle('dark');
    const isDarkMode = body.classList.contains('dark');
    
    modeText.innerText = isDarkMode ? 'Light Mode' : 'Dark Mode';
    localStorage.setItem('mode', isDarkMode ? 'dark' : 'light');
});

// Toggle the sidebar visibility on click of the hamburger icon
const hamburgerMenu = document.querySelector('.hamburger-menu');

hamburgerMenu.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});


});