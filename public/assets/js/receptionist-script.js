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

const editBtn = document.getElementById('editBtn');
const deleteBtn = document.getElementById('deleteBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');
const formElements = document.querySelectorAll('#trainerForm input, #trainerForm select');
const trainerIdInput = document.getElementById('trainer_id');

// Edit button functionality
editBtn.addEventListener('click', function () {
    // Enable the input fields for editing, except trainer_id
    formElements.forEach(element => {
        if (element !== trainerIdInput) {
            element.disabled = false;
        }
    });
    // Toggle the buttons' visibility
    saveBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'inline-block';
    editBtn.style.display = 'none';
    deleteBtn.style.display = 'none';
});

// Save button functionality
saveBtn.addEventListener('click', function () {
    // Submit the form after making changes
    document.getElementById('trainerForm').submit();
});

// Cancel button functionality
cancelBtn.addEventListener('click', function () {
    // Disable the input fields and revert changes, except for trainer_id
    formElements.forEach(element => {
        if (element !== trainerIdInput) {
            element.disabled = true;
        }
    });
    // Hide the save and cancel buttons, show the edit and delete buttons
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
    editBtn.style.display = 'inline-block';
    deleteBtn.style.display = 'inline-block';
});

// Get the canvas element
const ctx = document.getElementById('LineChart').getContext('2d');

// Generate current time and past 6 hours
const getCurrentTimeLabel = (hoursAgo) => {
  const date = new Date();
  date.setHours(date.getHours() - hoursAgo);
  return date.toLocaleTimeString('en-US', { 
    hour: 'numeric', 
    minute: '2-digit', 
    hour12: true 
  });
};

// Sample data - you can replace these numbers with actual member counts
const data = {
  labels: [
    getCurrentTimeLabel(5),
    getCurrentTimeLabel(4),
    getCurrentTimeLabel(3),
    getCurrentTimeLabel(2),
    getCurrentTimeLabel(1),
    getCurrentTimeLabel(0)
  ],
  datasets: [{
    label: 'Number of Members',
    data: [15, 25, 35, 45, 30, 20], // Sample member counts
    fill: true,
    borderColor: '#7380ec',
    backgroundColor: 'rgba(115, 128, 236, 0.1)',
    tension: 0.4,
    pointBackgroundColor: '#7380ec',
    pointBorderColor: '#fff',
    pointBorderWidth: 2,
    pointRadius: 5,
    pointHoverRadius: 7,
  }]
};

// Chart configuration
const config = {
  type: 'line',
  data: data,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          drawBorder: false,
          color: 'rgba(0, 0, 0, 0.1)'
        },
        ticks: {
          stepSize: 10
        }
      },
      x: {
        grid: {
          drawBorder: false,
          display: false
        }
      }
    },
    plugins: {
      legend: {
        position: 'top',
        labels: {
          boxWidth: 20,
          font: {
            family: "'Poppins', sans-serif"
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.7)',
        padding: 10,
        titleFont: {
          family: "'Poppins', sans-serif"
        },
        bodyFont: {
          family: "'Poppins', sans-serif"
        }
      }
    }
  }
};

// Create the chart
new Chart(ctx, config);