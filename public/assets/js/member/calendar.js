// calendar
let bookings = [
    { bdate: "01-11-2024", btime: "9:00 AM" },
    { bdate: "15-11-2024", btime: "10:00 AM" },
];

const timeslots = ["9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM"];


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

            const dateValue = `${currentDay.toString().padStart(2, '0')}-${(month + 1).toString().padStart(2, '0')}-${year}`;

            const bookingoftheDay = bookings.find((e) => e.bdate === dateValue);
                //highlight today
                if (currentDay === day && navigation === 0) {
                    dayBox.classList.add("today"); // Add class to today's date cell
                }
                // booking day
                if(bookingoftheDay){
                    const bookingBox = document.createElement("td");
                    bookingBox.classList.add("booked");
                    bookingBox.innerText = bookingoftheDay.btime;
                    dayBox.appendChild(bookingBox);
                }
                //bookingform
                dayBox.addEventListener("click", () =>{
                    // Remove `active` class from all cells
                    document.querySelectorAll(".day.active").forEach((e) => {
                    e.classList.remove("active");
                    });

                    // Add `active` class to the clicked cell
                    dayBox.classList.add("active");
                    showbookingForm(dateValue);
                });
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

    const closebtn = document.querySelectorAll(".btnClose");
    const deletebtn = document.querySelector("#btnDelete");
    const bookbtn = document.querySelector("#btnBook");

    closebtn.forEach((btn) =>{
        btn.addEventListener("click", closeBookingForm);
    });

    deletebtn.addEventListener("click", function(){
        bookings = bookings.filter((e) => e.bdate !== clicked);
        closeBookingForm();
    });

    bookbtn.addEventListener("click", function(){
        if(timeslotDropdown.value){
            timeslotDropdown.classList.remove("error");
            bookings.push({
                bdate : clicked,
                btime: timeslotDropdown.value,
            });
            timeslotDropdown.value = "";
            closeBookingForm();
            alert("Booking added successfully.");
        } else{
            timeslotDropdown.classList.add("error");
            alert("Please select a valid time slot.");
        }
    });
}

// bookingForm
let clicked = null;
const bookingForm = document.getElementById("bookingForm");
const viewbookingForm = document.getElementById("viewBooking");
const addbookingForm = document.getElementById("addBooking");
const timeslotDropdown = document.getElementById("timeslot");
const bookingTimeDisplay = document.querySelector("#bookingTime");

// showbookingform
function showbookingForm(dateValue){
    clicked = dateValue;
    const bookingoftheDay = bookings.find((e) => e.bdate === dateValue);
    if(bookingoftheDay){
        // already booked
        bookingTimeDisplay.innerText=bookingoftheDay.btime;
        viewbookingForm.style.display = "block";
        addbookingForm.style.display = "none";
    } else {
        // add new
        populateTimeslots(dateValue);
        addbookingForm.style.display = "block";
        viewbookingForm.style.display = "none";
    }
    bookingForm.style.display="block";
}

// closeform
function closeBookingForm(){
    viewbookingForm.style.display = "none";
    addbookingForm.style.display = "none";
    bookingForm.style.display="none";
    clicked = null;
    generateCalendar();
}

//timeslot
function populateTimeslots(dateValue){
    clicked = dateValue;
    timeslotDropdown.innerHTML = "";

    // default select
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    timeslotDropdown.appendChild(defaultOption);

    // get booked time slots
    const bookedSlots = bookings
        .filter((e) => e.bdate === dateValue)
        .map((e) => e.btime);
    // dropdown
    timeslots.forEach((slot) => {
        const option = document.createElement("option");
        option.value = slot;
        option.textContent = slot;

        if(bookedSlots.includes(slot)){
            option.disabled = true;
        }
        timeslotDropdown.appendChild(option);
    });
}




