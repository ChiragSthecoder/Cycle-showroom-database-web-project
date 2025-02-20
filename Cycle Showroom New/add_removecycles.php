<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

if ($_SESSION['username'] != 'admin') {
    header("location: login.php");
    exit;
}
?>
<?php
$showAlert = false;
$showError = false;
include 'partials/_dbconnect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get image name
    $image = $_FILES['image']['name'];
    // image file directory
    $target = "images/" . basename($image);
    $cyclename = $_POST["cyclename"];
    $compname = $_POST["company"];
    $type = $_POST["cycletype"];
    $avail = $_POST["avail"];
    $cycleno = $_POST["cycleno"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $sql = "INSERT INTO `cycles`(`cycle_photo`,`cycle_no`,`type`,`cycle_name`,`availability`,`company`,`price`,`description`) VALUES ('$image','$cycleno','$type','$cyclename','$avail','$compname','$price','$description')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $showAlert = true;
    } else {
        $showError = "Cycle Number already exists or the Company is not available";
    }
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Image uploaded successfully";
    } else {
        $msg = "Failed to upload image";
    }
}
$del = false;
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM `cycles` WHERE `cycle_no` = '$sno'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $del = true;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,500&display=swap" rel="stylesheet">
    <title>Add/Remove Cycles</title>
    <style>
        .form-control {
            border-radius: 25px;
        }

        .btn {
            border-radius: 25px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* The image used */
            background-image: url("4.jpg");

            /* Full height */
            height: 100%;

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;

        }

        img {
            float: left;
            margin: 5px;
            width: 230px;
            height: 150px;
        }
    </style>
</head>

<body>
    <?php require 'partials/_nav.php' ?>
    <?php
    if ($del) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Cycle information deleted successfully.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

    if ($showError) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Error!</strong>' . $showError . '
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
   </button>
   </div>';
    }

    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong>Cycle information added successfully.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
   </button>
   </div>';
    }
    ?>
    <h3 class="text-center my-4">Add Cycle information</h3>
    <div class="container my-4">
        <form action="/Cycle Showroom New/add_removecycles.php" method="post" style="display: flex;
    flex-direction: column;
    align-items: center;
    padding:15px" enctype="multipart/form-data">
            <div class="form-group col-md-6">
                <label for="cyclename">Cycle Name</label>
                <input type="text" class="form-control" id="cyclename" name="cyclename" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="company">Cycle Company</label>
                <input type="text" class="form-control" id="company" name="company" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="cycletype">Cycle Type</label>
                <input type="text" class="form-control" id="cycletype" name="cycletype" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="cycleno">Cycle Number</label>
                <input type="text" class="form-control" id="cycleno" aria-describedby="emailHelp" name="cycleno" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="avail">Availability</label>
                <input type="text" class="form-control" id="avail" name="avail" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" maxlength="20">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleFormControlTextarea1">Cycle Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
            </div>
            <div class="form-group col-md-6">
                <input type="hidden" name="size" value="1000000" class="form-control">
                <div class="form-group col-md-6">
                    <label for="exampleFormControlTextarea1">Cycle Photo</label>
                    <input type="file" name="image">
                </div>
            </div>

            <button type="submit" class="btn btn-primary col-md-1">Submit</button>
        </form>
    </div>
    <hr>

    <h3 class="text-center my-4">Available Cycle details</h3>
    <div class="container my-4">


        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Cycle Photo</th>
                    <th scope="col">Cycle Name</th>
                    <th scope="col">Company</th>
                    <th scope="col">Type</th>
                    <th scope="col">Cycle Number</th>
                    <th scope="col">Availability</th>
                    <th scope="col">Price</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM `cycles`";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
            <td> <img src='images/" . $row['cycle_photo'] . "' > </td>
            <td>" . $row['cycle_name'] . "</td>
            <td>" . $row['company'] . "</td>
            <td>" . $row['type'] . "</td>
            <td>" . $row['cycle_no'] . "</td>
            <td>" . $row['availability'] . "</td>
            <td>" . $row['price'] . "</td>
            <td>" . $row['description'] . "</td>
            <td> <button class='delete btn btn-sm btn-primary' id=d" . $row['cycle_no'] . ">Delete</button>  </td>
          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

        });
    </script>

    <script>
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to remove this cycle!?")) {
                    console.log("yes");
                    window.location = `/Cycle Showroom New/add_removecycles.php?delete=${sno}`;
                    // TODO: Create a form and use post request to submit a form
                } else {
                    console.log("no");
                }
            })
        })
    </script>
</body>

</html>
