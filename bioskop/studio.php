<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Database Bioskop</title>
</head>

<body>
    <header>
        <h3>Database Studio</h3>
    </header>

    <br>

    <table border="1">
    <thead>
        <tr>
            <th>ID Studio</th>
            <th>Kapasitas Studio</th>
            <th>Kelas</th>
            <th>Pilihan</th>

        </tr>
    </thead>
    <tbody>

        <?php
        $sql = "SELECT * FROM studio";
        $query = mysqli_query($db, $sql);

        while($studio = mysqli_fetch_array($query)){
            echo "<tr>";

            echo "<td>".$studio['ID_Studio']."</td>";
            echo "<td>".$studio['Kapasitas_Studio']."</td>";
            echo "<td>".$studio['Kelas']."</td>";

            echo "<td>";
            echo "<a href='form-edit.php?id=".$studio['ID_Studio']."'>Edit</a> | ";
            echo "<a href='hapus.php?id=".$studio['ID_Studio']."'>Hapus</a>";
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