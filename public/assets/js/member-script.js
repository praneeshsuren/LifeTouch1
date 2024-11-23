// Select all menu items
document.addEventListener("DOMContentLoaded", () =>{
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
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main');

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


// calendar
const calendarBody = document.querySelector('.calendarBody');
const monthYear = document.querySelector(".monthYear");
     // Check if .monthYear exists before proceeding
     if (!monthYear) {
        console.error("Element with class 'monthYear' not found in the DOM.");
        return; 
    }
const prevMonth = document.querySelector(".prevMonth");
const nextMonth = document.querySelector(".nextMonth");
const weekDays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
const gotoBtn = document.querySelector(".gotoBtn");
const todayBtn = document.querySelector(".todayBtn");
const dateInput = document.querySelector(".date-input");
var navigation = 0;



function generateCalendar(inputMonth = null, inputYear = null) {
    const date = new Date();
        if(navigation !=0){
            date.setMonth(date.getMonth() + navigation);
        }
    const day = date.getDate();
    const month = date.getMonth();
    const year = date.getFullYear();

    // If specific month/year is provided (from the date input), use that
    if (inputYear !== null && inputMonth !== null) {
        date.setFullYear(inputYear);
        date.setMonth(inputMonth - 1); // Month is zero-indexed, so subtract 1
    }
    
    monthYear.innerText = `${date.toLocaleDateString
        ("en-us", 
            {month: "long",})} ${year}`;

    const dayInMonth = new Date(year,month + 1, 0).getDate();//no.of days in current month
    const firstDayofMonth = new Date(year, month, 1);
    const emptyDays = firstDayofMonth.getDay();//get first day index

    //clr the calendar brdore regenrating
    calendarBody.innerHTML = "";

    for(let i=1; i <= dayInMonth + emptyDays; i++){
        if((i-1)%7 === 0){
            // create new row every 7 days
            var row = document.createElement("tr");
            calendarBody.appendChild(row);
        }
        const dayBox = document.createElement("td");
        dayBox.classList.add("day");
        if(i > emptyDays){
            const currentDay = i - emptyDays;
            dayBox.innerText = currentDay;
                //highlight today
                if (currentDay === day && navigation == 0) {
                    dayBox.classList.add("today"); // Add class to today's date cell
                }
        } else{
            dayBox.classList.add("plain");
        }
        row.append(dayBox);
    }  
}

function buttons(){
if(prevMonth){
    prevMonth.addEventListener("click", ()=>{
        navigation--;
        generateCalendar();
    });
}
if(nextMonth){
    nextMonth.addEventListener("click", ()=>{
        navigation++;
        generateCalendar();
    });
}
    const date = new Date();
if(todayBtn){ 
    todayBtn.addEventListener("click", ()=>{
       navigation = 0;
       generateCalendar();
    });
}
if(dateInput){
    dateInput.addEventListener("input", (e) => {
        // Allow only numbers and slash
        dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
    
        // Add a slash automatically after two digits
        if (dateInput.value.length === 2 && e.inputType !== "deleteContentBackward") {
            dateInput.value += "/";
        }
    
        // Limit the input to 7 characters (MM/YYYY)
        if (dateInput.value.length > 7) {
            dateInput.value = dateInput.value.slice(0, 7);
        }
    });
}
if(gotoBtn){
    gotoBtn.addEventListener("click", () => {
        const dateArr = dateInput.value.split("/");
        if (dateArr.length === 2) {
            const inputMonth = parseInt(dateArr[0], 10);
            const inputYear = parseInt(dateArr[1], 10);
    
            // Validate month and year
            if (
                inputMonth >= 1 && inputMonth <= 12 && // Month between 1 and 12
                inputYear >= 1900 && inputYear <= 2100 && // Year in valid range
                dateArr[1].length === 4 // Year has 4 digits
            ) {
                // Calculate navigation based on input month/year
                const currentDate = new Date();
                navigation = (inputYear - currentDate.getFullYear()) * 12 + (inputMonth - 1) - currentDate.getMonth();
                generateCalendar(inputMonth, inputYear);
            } else {
                alert("Invalid date.");
            }
        } else {
            alert("Invalid date format. Please use MM/YYYY.");
        }
    });
}
}
buttons();
generateCalendar();
});
