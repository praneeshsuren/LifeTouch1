let menu = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');

// Toggle the menu when clicked
menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navbar.classList.toggle('active');
}

// Close the menu when scrolling
window.onscroll = () => {
    menu.classList.remove('bx-x');
    navbar.classList.remove('active');
}

const textArray = ['Physical Fitness', 'Weight Gain', 'Strength Training', 'Fat Loss', 'Weight Lifting', 'Running'];
let currentIndex = 0;
let currentText = '';
let currentCharIndex = 0;
const typingSpeed = 100; // Speed of typing
const backSpeed = 60; // Speed of backspacing
const backDelay = 1000; // Delay before starting to backspace
const typingElement = document.querySelector('.multiple-text');

function typeText() {
    if (currentCharIndex < textArray[currentIndex].length) {
        currentText += textArray[currentIndex].charAt(currentCharIndex);
        typingElement.textContent = currentText;
        currentCharIndex++;
        setTimeout(typeText, typingSpeed);
    } else {
        setTimeout(backspaceText, backDelay);
    }
}

function backspaceText() {
    if (currentCharIndex > 0) {
        currentText = currentText.slice(0, -1);
        typingElement.textContent = currentText;
        currentCharIndex--;
        setTimeout(backspaceText, backSpeed);
    } else {
        // Add a space or reset positioning after erasing
        typingElement.textContent = ''; // You can also reset other properties if needed

        // Give a small gap before starting to type the next word
        setTimeout(() => {
            currentIndex = (currentIndex + 1) % textArray.length;
            setTimeout(typeText, typingSpeed);
        }, 500); // A small delay to ensure separation
    }
}

// Start typing effect
typeText();
