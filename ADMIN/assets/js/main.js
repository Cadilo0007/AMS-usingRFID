document.addEventListener("DOMContentLoaded", function() {
  // add hovered class to selected list item
  let list = document.querySelectorAll(".navigation li");

  function activeLink() {
      list.forEach((item) => {
          item.classList.remove("hovered");
      });
      this.classList.add("hovered");
  }

  list.forEach((item) => item.addEventListener("mouseover", activeLink));

  // Menu Toggle
  let toggle = document.querySelector(".toggle");
  let navigation = document.querySelector(".navigation");
  let main = document.querySelector(".main");

  toggle.onclick = function () {
      navigation.classList.toggle("active");
      main.classList.toggle("active");
  };
});
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName(".close");

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
let subMenu = document.getElementById("subMenu");

function toggleMenu(){
  subMenu.classList.toggle("open-menu");
  
}

function updateTable() {
    var limit = document.getElementById('entriesSelect').value;
    var url = new URL(window.location.href);
    url.searchParams.set('limit', limit);
    url.searchParams.set('page', 1); // Reset to first page when changing limit
    window.location.href = url.toString();
}


    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

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
    //

// Add event listener to the search input
document.getElementById('searchInput').addEventListener('keyup', function() {
  var searchValue = this.value.toLowerCase(); // Convert search input to lowercase
  var rows = document.querySelectorAll('#departmentTable tr'); // Select all rows in the table body

  // Loop through all table rows
  rows.forEach(function(row) {
      var rowText = row.textContent.toLowerCase(); // Get text content of each row and convert it to lowercase

      // Check if the row contains the search value
      if (rowText.includes(searchValue)) {
          row.style.display = ''; // Show the row if it matches
      } else {
          row.style.display = 'none'; // Hide the row if it doesn't match
      }
  });

  // Update pagination after filtering
  updatePagination();
});

// Update pagination based on the visible rows
function updatePagination() {
  var rows = document.querySelectorAll('#departmentTable tr');
  var visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
  var limit = parseInt(document.getElementById('entriesSelect').value);

  // Show only the number of rows according to the selected limit
  visibleRows.forEach((row, index) => {
      if (index < limit) {
          row.style.display = '';
      } else {
          row.style.display = 'none';
      }
  });
}

// Update table rows visibility when entries limit is changed
function updateTable() {
  var rows = document.querySelectorAll('#departmentTable tr');
  var limit = parseInt(document.getElementById('entriesSelect').value);

  // Show the rows according to the new limit
  rows.forEach((row, index) => {
      if (index < limit) {
          row.style.display = '';
      } else {
          row.style.display = 'none';
      }
  });

  // Adjust pagination after updating the table
  updatePagination();
}

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





