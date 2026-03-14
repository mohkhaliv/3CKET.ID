document.addEventListener("DOMContentLoaded", function () {
  const seatsContainer = document.getElementById("seatsContainer");
  const numberOfRows = 10; // Adjust the number of rows
  const seatsPerRow = 9; // Adjust the number of seats per column
  const soldSeats = ["2_5", "4_8", "8_1"]; // Define sold seat numbers
  const seatPrice = 50000; // Define the price per seat in Rupiah

  // Function to convert number to alphabet (A, B, C...)
  function getAlphabetChar(number) {
    return String.fromCharCode(65 + number);
  }

  // Create columns with seats dynamically
  for (let seatNum = 1; seatNum <= seatsPerRow; seatNum++) {
    const columnElement = document.createElement("div");
    columnElement.classList.add("column");

    for (let row = 0; row < numberOfRows; row++) {
      const rowLabel = getAlphabetChar(row); // Convert row number to alphabet

      const seat = document.createElement("div");
      seat.classList.add("seat");

      seat.innerText = `${rowLabel}${seatNum}`;

      seat.dataset.row = rowLabel;
      seat.dataset.seatNum = seatNum;

      // Set different classes for available, selected, and sold seats
      if (soldSeats.includes(`${rowLabel}_${seatNum}`)) {
        seat.classList.add("sold");
      } else {
        seat.classList.add("available");
        seat.addEventListener("click", function () {
          if (!this.classList.contains("sold")) {
            this.classList.toggle("selected");
            calculateTotalPrice();
          }
        });
      }

      columnElement.appendChild(seat);
    }

    seatsContainer.appendChild(columnElement);
  }

  // Function to calculate the total price based on selected seats
  // Function to calculate the total price based on selected seats
  function calculateTotalPrice() {
    const selectedSeats = document.querySelectorAll(".selected");
    const totalPriceDisplay = document.getElementById("totalPrice");
    const selectedSeatsList = document.getElementById("selectedSeatsList");
    const totalPriceInput = document.getElementById("totalPriceInput");

    // Calculate the total price based on the number of selected seats
    let totalPrice = selectedSeats.length * seatPrice;

    // Display the total price in an element
    totalPriceDisplay.textContent = `Rp ${totalPrice.toLocaleString()}`;

    // Update the selected seats list
    const seatNames = Array.from(selectedSeats).map((seat) => seat.innerText);

    selectedSeatsList.innerHTML = `Selected Seats: ${seatNames.join(" ")}`;

    // Update the hidden input field with the total price
    totalPriceInput.value = totalPrice;
  }

  // Adjust the styles of specific columns
  const firstColumn = document.querySelector(".column:nth-child(1)");
  firstColumn.style.marginLeft = "20px"; // Set a left margin of 20px

  const secondColumn = document.querySelector(".column:nth-child(2)");
  secondColumn.style.marginRight = "30px"; // Set a right margin of 30px

  const eighthColumn = document.querySelector(".column:nth-child(8)");
  eighthColumn.style.marginLeft = "30px"; // Set a left margin of 30px

  const ninthColumn = document.querySelector(".column:nth-child(9)");
  ninthColumn.style.marginRight = "20px"; // Set a right margin of 20px
});
