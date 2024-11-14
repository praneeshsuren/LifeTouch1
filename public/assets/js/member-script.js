// Select all menu items
document.addEventListener("DOMContentLoaded", function() {
const body = document.querySelector('body');
const menuItems = document.querySelectorAll('.menu ul li a');
const menuButton = document.querySelector('.menu-btn');
const modeSwitch = document.querySelector('.toggle-switch');
const modeText = document.querySelector('.mode-text');
const areaChartContainer = document.querySelector('#areaChart');
// Function to remove the active class from all items
function removeActiveClass() {
    menuItems.forEach(item => {
        item.parentElement.classList.remove('active');
    });
}

// Add click event listener to each menu item
if (menuItems.length>0){
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

if(menuButton){
menuButton.addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar){
    sidebar.classList.toggle('active');
    }
});
}

// Area Chart with ApexCharts for Busy Hours
// Generate labels for time range from 5 AM to 10 PM
if(areaChartContainer){
function generateTimeRangeLabels(startHour, endHour) {
    const labels = [];
    for (let hour = startHour; hour <= endHour; hour++) {
        let period = hour < 12 ? 'AM' : 'PM';
        let displayHour = hour % 12 === 0 ? 12 : hour % 12; // Convert 24-hour format to 12-hour format
        labels.push(`${displayHour} ${period}`);
    }
    return labels;
}

// Generate data for 18 hours (matching the 5 AM to 10 PM range)
function generateHourlyData(hours) {
    return Array.from({ length: hours }, () => Math.floor(Math.random() * 100) + 20);
}

// Updated chart options with X-axis category labels from 5 AM to 10 PM
const chartOptions = {
    chart: {
        type: 'area',
        height: 350,
        toolbar: {
            show: false
        },
        background: 'transparent'
    },
    series: [{
        name: 'Number of Members',
        data: generateHourlyData(18) // Data for 18 hours (5 AM to 10 PM)
    }],
    xaxis: {
        type: 'category',
        categories: generateTimeRangeLabels(5, 22), // Time labels from 5 AM to 10 PM (5 to 22)
        title: {
            text: 'Time (Working Hours)',
        },
        labels: {
            rotate: -45,  // Rotate labels to prevent overlap
            style: {
                colors: [],  // This will be set later based on the mode
                fontSize: '12px'
            }
        },
        axisBorder: {
            color: ''  // Set later based on mode
        },
        axisTicks: {
            color: ''  // Set later based on mode
        }
    },
    yaxis: {
        title: {
            text: 'Number of Members',
        },
        labels: {
            style: {
                colors: [],  // Set later based on mode
                fontSize: '12px'
            }
        }
    },
    colors: ['#695CFE'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 90, 100]
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 2
    },
    grid: {
        strokeDashArray: 4,
        yaxis: {
            lines: {
                show: true
            }
        },
        borderColor: ''  // Set later based on mode
    },
    tooltip: {
        style: {
            fontSize: '12px',
        },
        x: {
            format: 'HH:mm'
        },
    }
};
var chart = new ApexCharts(document.querySelector("#areaChart"), chartOptions);
chart.render();

// Function to update chart theme
function updateChartTheme(isDarkMode) {
    const colors = {
        text: isDarkMode ? '#E0E0E0' : '#333333',
        subtext: isDarkMode ? '#B0B0B0' : '#555555',
        grid: isDarkMode ? '#333333' : '#E0E0E0',
        areaColor: isDarkMode ? '#8B5CF6' : '#695CFE'
    };
    if(chart){
    chart.updateOptions({
        theme: {
            mode: isDarkMode ? 'dark' : 'light'
        },
        colors: [colors.areaColor],
        xaxis: {
            title: {
                style: { color: colors.text }
            },
            labels: {
                style: { colors: Array(18).fill(colors.subtext) }  // Apply color to X-axis labels
            },
            axisBorder: { color: colors.grid },
            axisTicks: { color: colors.grid }
        },
        yaxis: {
            title: {
                style: { color: colors.text }
            },
            labels: {
                style: { colors: colors.subtext }
            }
        },
        grid: {
            borderColor: colors.grid
        },
        tooltip: {
            theme: isDarkMode ? 'dark' : 'light'
        }
    });
    }
}

// Initialize mode based on localStorage
let mode = localStorage.getItem('mode') || 'light';
body.classList.toggle('dark', mode === 'dark');
modeText.innerText = mode === 'dark' ? 'Light Mode' : 'Dark Mode';
updateChartTheme(body.classList.contains('dark'));
}

// Toggle Dark/Light Mode
if(modeSwitch && modeText){
modeSwitch.addEventListener('click', () => {
    body.classList.toggle('dark');
    const isDarkMode = body.classList.contains('dark');
    
    modeText.innerText = isDarkMode ? 'Light Mode' : 'Dark Mode';
    localStorage.setItem('mode', isDarkMode ? 'dark' : 'light');
    
    if(areaChartContainer){
        updateChartTheme(isDarkMode);
    }
});
}

// Initialize chart with current theme
updateChartTheme(body.classList.contains('dark'));


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