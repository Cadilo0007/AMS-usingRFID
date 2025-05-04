function updateClock() {
  const now = new Date();
  const hours = now.getHours();
  const minutes = now.getMinutes();
  const seconds = now.getSeconds();
  const isPM = hours >= 12;
  const displayHour = hours % 12 || 12;
  const formattedTime = `${displayHour}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${isPM ? 'PM' : 'AM'}`;
  const formattedDate = now.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
  
  document.getElementById('clock').textContent = formattedTime;
  document.getElementById('date').textContent = formattedDate;
}

setInterval(updateClock, 1000); // Update every second
updateClock(); // Initial call



//the modal
let modal = document.getElementById("myModal");

//the button that opens the modal
let btn = document.getElementById("myBtn");

//the <span> element that closes the modal
let span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

// Clear the form inputs after a successful submission
rfidInput.value = '';  // Reset RFID input field
document.querySelector('select[name="action"]').selectedIndex = 0;  // Reset dropdown

// Optionally, hide the modal after a few seconds automatically
setTimeout(function() {
    modal.style.display = 'none';
}, 2000);  // Hide the modal after 2 seconds

document.querySelectorAll('td').forEach(function(td) {
  if (td.innerText.trim() === '12:00 AM') {
      td.innerText = ''; // Hide the 12:00 AM
  }
});


