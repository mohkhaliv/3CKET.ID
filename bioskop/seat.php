<?php
include("config.php");
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: index.php");
    exit();
}

include("config.php");

if (isset($_GET['id'])) {
    $filmId = $_GET['id'];
} else {
    echo "Invalid film ID.";
    exit();
}

// Fetch the selected seats for the current film from the payment_proofs table
$selectedSeatsQuery = "SELECT selected_seats FROM payment_proofs WHERE film_id = ?";
$stmt = $db->prepare($selectedSeatsQuery);
$stmt->bind_param("s", $filmId);
$stmt->execute();
$result = $stmt->get_result();

$selectedSeats = [];

while ($row = $result->fetch_assoc()) {
  // Split the selected seats string into an array
  $seats = explode(" ", $row['selected_seats']);
  foreach ($seats as $seat) {
      // Convert seat information to the format used in seat checkboxes
      preg_match('/([A-Z])(\d+)/', $seat, $matches);
      if (count($matches) === 3) {
          $rowIndex = ord($matches[1]) - ord('A');
          $seatNum = intval($matches[2]) - 1;
          // Convert to 0-based indexing for seat number only
          $formattedSeat = "${rowIndex}_${seatNum}";
          $selectedSeats[] = $formattedSeat;
      }
  }
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cinema Seat Selection</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .selected {
            background-color: #ffcc00;
        }

        #buyButton {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<h1>Pilih Kursi Anda</h1>

<form id="seatsForm" action="payment.php?id=<?php echo $filmId; ?>" method="post">
        <table>
            <tr>
                <th></th> <!-- Empty cell for the row labels -->
                <?php for ($seatNum = 1; $seatNum <= 9; $seatNum++) : ?>
                    <th><?php echo $seatNum; ?></th>
                <?php endfor; ?>
            </tr>

            <?php for ($row = 0; $row < 10; $row++) : ?>
                <tr>
                    <th><?php echo chr(65 + $row); ?></th>
                    <?php for ($seatNum = 1; $seatNum <= 9; $seatNum++) : ?>
                        <td>
                            <?php
                            $seatId = "${row}_${seatNum}";
                            $isChecked = in_array($seatId, $selectedSeats);
                            ?>
                            <input type="checkbox" class="seatCheckbox" id="<?php echo $seatId; ?>"
                                data-row="<?php echo chr(65 + $row); ?>" data-seatNum="<?php echo $seatNum; ?>"
                                <?php echo $isChecked ? 'checked' : ''; ?> <?php echo $isChecked ? 'disabled' : ''; ?>>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>

        <p>Harga Total: <span id="totalPrice">Rp 0</span></p>
        <input type="hidden" id="selectedSeatsInput" name="selectedSeats" value="">
        <input type="hidden" id="totalPriceInput" name="totalPrice" value="">
        <div id="selectedSeatsList"></div>
        <button type="submit" id="buyButton">Beli Kursi yang Dipilih</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const seatCheckboxes = document.querySelectorAll('.seatCheckbox');
    const seatPrice = 50000; // Set the price per seat in Rupiah

    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            calculateTotalPrice();
        });

        // Disable the checkbox if it's one of the permanently selected seats
        const isPermanentlySelected = checkbox.checked;
        if (isPermanentlySelected) {
            checkbox.disabled = true;
        }
    });

    function calculateTotalPrice() {
        const selectedSeats = document.querySelectorAll(".seatCheckbox:checked");
        const totalPriceDisplay = document.getElementById("totalPrice");
        const selectedSeatsList = document.getElementById("selectedSeatsList");
        const totalPriceInput = document.getElementById("totalPriceInput");

        // Filter out disabled checkboxes from the selected seats
        const validSelectedSeats = Array.from(selectedSeats).filter(seat => !seat.disabled);

        // Create an array of seat names using data attributes
        const seatNames = validSelectedSeats.map((seat) => {
            const row = seat.getAttribute("data-row");
            const seatNum = seat.getAttribute("data-seatNum");
            return row && seatNum ? `${row}${seatNum}` : null;
        }).filter(Boolean); // Filter out null values

        // Sort the seat names alphabetically and numerically
        seatNames.sort((a, b) => {
            const rowA = a.charAt(0);
            const numA = parseInt(a.substring(1));

            const rowB = b.charAt(0);
            const numB = parseInt(b.substring(1));

            if (rowA === rowB) {
                return numA - numB;
            }

            return rowA.localeCompare(rowB);
        });

        // Display the sorted seat names
        selectedSeatsList.innerHTML = `Selected Seats: ${seatNames.join(" ")}`;

        // Calculate and display the total price
        let totalPrice = validSelectedSeats.length * seatPrice;
        totalPriceDisplay.textContent = `Rp ${totalPrice.toLocaleString()}`;

        // Update the hidden input field with the sorted seat names
        totalPriceInput.value = totalPrice;
        document.getElementById("selectedSeatsInput").value = seatNames.join(" ");
    }
});

function redirectToPayment() {
    const selectedSeatsInput = document.getElementById("selectedSeatsInput");
    const totalPriceInput = document.getElementById("totalPriceInput");
    const selectedSeats = document.querySelectorAll(".seatCheckbox:checked");

    // Filter out disabled checkboxes from the selected seats
    const validSelectedSeats = Array.from(selectedSeats).filter(seat => !seat.disabled);

    selectedSeatsInput.value = validSelectedSeats.map((seat) => seat.id).join(" ");

    calculateTotalPrice();

    console.log("Selected Seats:", validSelectedSeats.map((seat) => seat.id).join(" "));
    console.log("Total Price:", totalPriceInput.value);
}

    </script>
</body>
</html>