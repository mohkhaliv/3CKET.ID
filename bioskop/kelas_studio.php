<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>Database Bioskop</title>
</head>

<body>
    <header>
        <h3>Database Kelas Studio</h3>
    </header>

    <br>

    <table border="1">
    <thead>
        <tr>
            <th>Kelas</th>
            <th>Harga</th>
            <th>Pilihan</th>
            
        </tr>
    </thead>
    <tbody>

        <?php
        $sql = "SELECT * FROM kelas_studio";
        $query = mysqli_query($db, $sql);

        while($kelas_studio = mysqli_fetch_array($query)){
            echo "<tr>";

            echo "<td>".$kelas_studio['Kelas']."</td>";
            echo "<td>".$kelas_studio['Harga']."</td>";

            echo "<td>";
            echo "<a href='form-edit.php?id=".$kelas_studio['Kelas']."'>Edit</a> | ";
            echo "<a href='hapus.php?id=".$kelas_studio['Kelas']."'>Hapus</a>";
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