//Announcements Page
document.addEventListener("DOMContentLoaded", function() {
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

if(markAllAsReadButton){
markAllAsReadButton.addEventListener('click', function() {
    unReadAnnouncements.forEach((announcement) => {
        announcement.classList.remove('unread');
    });
    updateUnreadCount();
});
}

function updateUnreadCount() {
    const currentUnreadCount = document.querySelectorAll('.unread').length;

    if (unReadAnnouncementsCount) { // Check if the element exists
        unReadAnnouncementsCount.textContent = currentUnreadCount + ' Unread!';
        unReadAnnouncementsCount.style.display = currentUnreadCount === 0 ? 'none' : 'flex';
    } else {
        console.error('Element with ID num-of-announcements not found.');
    }
}
});