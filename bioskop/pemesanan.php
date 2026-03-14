<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>Database Bioskop</title>
</head>

<body>
    <header>
        <h3>Database Pemesanan</h3>
    </header>

    <br>

    <table border="1">
    <thead>
        <tr>
            <th>ID Pemesan</th>
            <th>ID Tiket</th>
            <th>ID Petugas</th>
            <th>ID Jadwal</th>
            <th>Seat Dipesan</th>
            <th>Pilihan</th>

        </tr>
    </thead>
    <tbody>

        <?php
        $sql = "SELECT * FROM pemesanan";
        $query = mysqli_query($db, $sql);

        while($pemesanan = mysqli_fetch_array($query)){
            echo "<tr>";

            echo "<td>".$pemesanan['ID_Pemesan']."</td>";
            echo "<td>".$pemesanan['ID_Tiket']."</td>";
            echo "<td>".$pemesanan['ID_Petugas']."</td>";
            echo "<td>".$pemesanan['ID_Jadwal']."</td>";
            echo "<td>".$pemesanan['Seat_Dipesan']."</td>";

            echo "<td>";
            echo "<a href='form-edit.php?id=".$pemesanan['ID_Tiket']."'>Edit</a> | ";
            echo "<a href='hapus.php?id=".$pemesanan['ID_Tiket']."'>Hapus</a>";
            echo "</td>";

            echo "</tr>";
        }
        ?>

    </tbody>
    </table>

    <p>Total: <?php echo mysqli_num_rows($query) ?></p>

    <nav>
        <ul>
            <li><a href="index.php">Kembali</a></li>
        </ul>
    </nav>
    </body>
</html>