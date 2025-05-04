<script src="assets/js/main.js"></script>
<script>
    function printTable() {
        var printContents = document.querySelector('.attendanceView-Em').innerHTML; // Select the table container
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents; // Replace the body with table contents
        window.print(); // Trigger the print dialog
        document.body.innerHTML = originalContents; // Restore original contents
        window.location.reload(); // Refresh the page to restore any JavaScript functionality lost after print
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
    var searchValue = this.value.toLowerCase(); // Convert search input to lowercase
    var rows = document.querySelectorAll('#departmentTable tr'); // Select all rows in the table body

    // Loop through all table rows
    rows.forEach(function(row) {
        // Get text content of each row and convert it to lowercase
        var rowText = row.textContent.toLowerCase();

        // Check if the row contains the search value
        if (rowText.includes(searchValue)) {
            row.style.display = ''; // Show the row if it matches
        } else {
            row.style.display = 'none'; // Hide the row if it doesn't match
        }
    });
    });

    
</script>
<script>
    function filterTable() {
        const input = document.querySelector('input[type="search"]');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('table.All');
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            const tdArray = tr[i].getElementsByTagName("td");
            let rowContainsFilter = false;

            for (let td of tdArray) {
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rowContainsFilter = true;
                        break;
                    }
                }
            }

            if (rowContainsFilter) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
<script>
    function updateTable() {
        const selectedLimit = document.getElementById('entriesSelect').value;
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', selectedLimit);
        window.location.search = urlParams.toString();
    }
</script>



<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>