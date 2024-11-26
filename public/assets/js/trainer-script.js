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

//Announcements Page
const unReadAnnouncements = document.querySelectorAll('.unread');
const unReadAnnouncementsCount = document.getElementById('num-of-announcements');
const markAllAsReadButton = document.getElementById('mark-as-read');

updateUnreadCount();

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.remove('unread');
            updateUnreadCount();
            // Once the announcement is marked as read, we don't need to observe it anymore
            observer.unobserve(entry.target);
        }
    });
}, {
    root: null, // Use the viewport as the root
    rootMargin: '0px',
    threshold: 0.5 // Trigger when 50% of the element is visible
});

// Start observing each unread announcement
unReadAnnouncements.forEach((announcement) => {
    observer.observe(announcement);
});

markAllAsReadButton.addEventListener('click', function() {
    unReadAnnouncements.forEach((announcement) => {
        announcement.classList.remove('unread');
    });
    updateUnreadCount();
});

function updateUnreadCount() {
    const currentUnreadCount = document.querySelectorAll('.unread').length;
    unReadAnnouncementsCount.textContent = currentUnreadCount + ' Unread!';
    unReadAnnouncementsCount.style.display = currentUnreadCount === 0 ? 'none' : 'flex';
}