// Select all menu items
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

menuButton.addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
});

modeSwitch.addEventListener('click', () => {
    body.classList.toggle('dark');

    if(body.classList.contains('dark')) {
        modeText.innerText = 'Light Mode';
    }else{
        modeText.innerText = 'Dark Mode';
    }
});