// Helper to get query params
document.addEventListener("DOMContentLoaded", () =>{
    generateCalendar();
    buttons();
});
    
const calendarBody = document.querySelector('.calendarBody');
const monthYear = document.querySelector(".monthYear"); 
const prevMonth = document.querySelector(".prevMonth");
const nextMonth = document.querySelector(".nextMonth");
const weekDays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
const gotoBtn = document.querySelector(".gotoBtn");
const todayBtn = document.querySelector(".todayBtn");
const dateInput = document.querySelector(".date-input");
var navigation = 0;

const modal = document.getElementById('bookingModal');
const closeModal = document.querySelector('.modal .close');
const modalDate = document.getElementById('modalDate');
const selectedDateInput = document.getElementById('selectedDate');
    
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

    //clr the calendar before regenrating
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

            const dateValue = `${currentDay.toString().padStart(2, '0')}-${(month + 1).toString().padStart(2, '0')}-${year}`;
                //highlight today
                if (currentDay === day && navigation === 0) {
                    dayBox.classList.add("today"); // Add class to today's date cell
                }
        } else{
            dayBox.classList.add("plain");
        }
        row.append(dayBox);
    } 
    // Add plain class to the remaining empty days in the last week
    const totalCells = dayInMonth + emptyDays;
    const remainingCells = 7 - (totalCells % 7); 

    if (remainingCells < 7) { 
        for (let j = 0; j < remainingCells; j++) {
            const dayBox = document.createElement("td");
            dayBox.classList.add("plain");
            row.append(dayBox);
        }
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
                    dateInput.value = "";
                } else {
                    alert("Invalid date or year.");
                }
            } else {
                alert("Invalid date format. Please use MM/YYYY.");
            }
        });
    }
}

// Open modal on date box click
    calendarBody.addEventListener('click', function (event) {
        const clickedElement = event.target;

        // Check if the clicked element is a date box
        if (clickedElement.classList.contains('day') && !clickedElement.classList.contains('plain')) {
            const selectedDate = clickedElement.innerText;
            const currentMonthYear = document.querySelector('.monthYear').innerText;

            // Set the modal's date display and hidden input field
            modalDate.innerText = `${selectedDate} ${currentMonthYear}`;
            selectedDateInput.value = `${selectedDate} ${currentMonthYear}`;

            // Show the modal
            modal.style.display = 'block';
        }
    });

    // Close modal when 'x' is clicked
    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });
    

// const modal = document.getElementById('bookingModal');
// const modalDate = document.getElementById('modalDate');
// const closeBtn = document.querySelector('.close');
// const selectedDateInput = document.getElementById('selectedDate');
// const selectedTimeslotInput = document.getElementById('selectedTimeslot');
        
// // // Add event listener to all clickable calendar cells
// document.querySelectorAll('.calendar .clickable').forEach(cell => {cell.addEventListener('click', function () {
//     const selectedDate = this.getAttribute('data-date');
//     modalDate.textContent = selectedDate;
//     selectedDateInput.value = selectedDate;
//     selectedTimeslotInput.value = ''; 
//     selectedTimeslotInput.placeholder = 'Select the Timeslot';

//     modal.style.display = 'block';
// });
// });

// // Close the modal when clicking on the 'x' button
// closeBtn.addEventListener('click', function () {
//     modal.style.display = 'none';
// });


