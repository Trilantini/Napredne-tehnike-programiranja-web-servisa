<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="This site is for demonstration purposes only and is not intended for actual use by visitors. It showcases various features, designs, and functionalities that can be incorporated into a real website." />
    <meta name="author" content="Daniel Gluhak" />

    <!-- Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Google Font Roboto-->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />
    <!-- Font Awesome free icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Outside css-->
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>" />

    <title>FilmGeekCentral | My profile</title>
</head>

<body>
    <?php
    include "connect.php";
    define('IMGPATH', 'img/');
    session_start();

    if (isset($_SESSION['$username']) && isset($_SESSION['$logedin'])) {
        $MyUsername = $_SESSION['$username'];
    ?>

        <!-- Top Navigation -->
        <div id="navigation" class="container-fluid">
            <div class="container d-flex flex-wrap justify-content-center py-2 mb-1">
                <div class="col-md-5 mb-3 mb-md-0 d-flex align-items-center me-md-auto">
                    <i class="fa-solid fa-f fa-beat fa-lg"></i><a href="#" id="logo" class="ms-n2 logo navbar-brand text-decoration-none">
                        ilm Geek Central
                    </a>
                </div>
                <ul class="col-md-5 align-self-center nav justify-content-end">
                    <li class="nav-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="category.php?id=Film">Movies</a>
                    </li>
                    <li class="nav-item">
                        <a href="category.php?id=Tv">Shows</a>
                    </li>
                    <li class="nav-item">
                        <a href="reviews.php">Reviews</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="MainPage">My profile</a>
                        <div class="dropdown-content">
                            <a href="profile.php">Account</a>
                            <a href="create.php">Create Article</a>
                            <a href="signout.php">Log out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <?php
        #UPDATE ICON        
        if (isset($_POST['submitIcon'])) {
            $icon = $_POST['avatar'];
            $query = "UPDATE user SET avatar = $icon WHERE id=" . $_SESSION['$userID'];
            $result = mysqli_query($dbc, $query);
            if (!$result) {
                printf("Error: %s\n", mysqli_error($dbc));
                exit();
            }
            print '
                <script>
                    location.href = location.href;
                </script>
                ';
        }
        ?>

        <?php
        #UPDATE ACCOUNT
        $userExist = false;
        if (isset($_POST['submitAccount'])) {
            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $PasswordHash = password_hash($password, CRYPT_BLOWFISH);
            $sql = "SELECT username FROM user WHERE username=?";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
            }
            if (mysqli_stmt_num_rows($stmt) > 0 && $username != $MyUsername) {
                print '
                <script>
                    alert("Username already exists!");
                </script>';
                $message = "Username already exists!";
                $userExist = true;
            } else {
                $query = "UPDATE user SET name='$name', surname='$surname', username='$username', password='$PasswordHash' WHERE id=" . $_SESSION['$userID'];
                $result = mysqli_query($dbc, $query);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }

                $_SESSION['$username'] = $username;
                //UPDATE all Movie articles with new name and surname                   
                $sql = "UPDATE movies SET autor='$name $surname' WHERE userID =" . $_SESSION['$userID'];
                $newresult = mysqli_query($dbc, $sql);
                if (!$newresult) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }

                //UPDATE all Review articles with new name and surname
                $sql = "UPDATE review SET autor='$name $surname' WHERE userID =" . $_SESSION['$userID'];
                $newresult = mysqli_query($dbc, $sql);
                if (!$newresult) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }

                //UPDATE all TV articles with new name and surname
                $sql = "UPDATE tv_show SET autor='$name $surname' WHERE userID =" . $_SESSION['$userID'];
                $newresult = mysqli_query($dbc, $sql);
                if (!$newresult) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }

                print '
                        <script>
                            location.href = location.href;
                        </script>
                    ';
            }
        }
        ?>

        <!-- Header with image -->
        <header class="container header-info">
            <div class="row ">
                <div class="profile-image m-4 col-md-4">
                    <div class="container">
                        <div class="row col-md-12 col-sm-4">
                            <div class="image-icon d-flex justify-content-between mx-auto col-md-6 p-0 border border-2 border-black overflow-hidden">
                                <?php
                                $query = "SELECT profile_icon.id, profile_icon.name, profile_icon.image FROM profile_icon INNER JOIN user ON profile_icon.id = user.avatar  WHERE username = '$MyUsername'";
                                $result = mysqli_query($dbc, $query);
                                if (!$result) {
                                    printf("Error: %s\n", mysqli_error($dbc));
                                    exit();
                                }
                                $row = mysqli_fetch_array($result);
                                $imageSrc = 'data:image/png;base64,' . base64_encode($row['image']);
                                $avatarID = $row['id'];
                                print '<img src="' . $imageSrc . '" alt="' . $row['name'] . '" >';
                                ?>
                            </div>
                        </div>
                        <div class="row col-md-12 col-sm-4">
                            <div class="image-edit py-3 d-flex justify-content-between mx-auto col-md-6">
                                <button type="button" class=" icon-link btn p-0 text-center mx-auto btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                    Edit
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-welcome m-4 col-md-7 col-sm-12">
                    <div class="container mt-3">
                        <div class="row col-md-12">
                            <div class="message ">
                                <h1>Welcome,
                                    <strong>
                                        <?php
                                        $query = "SELECT * FROM user WHERE username = '$MyUsername'";
                                        $result = mysqli_query($dbc, $query);
                                        if (!$result) {
                                            printf("Error: %s\n", mysqli_error($dbc));
                                            exit();
                                        }
                                        $row = mysqli_fetch_array($result);
                                        print ' ' . $row['name'] . ' ' . $row['surname'];
                                        $userID = $row['id'];
                                        $_SESSION['$userID'] = $userID;
                                        ?>
                                    </strong>
                                </h1>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="account-buttons d-flex gap-2 mb-1">
                                <button type="button" class="icon-link btn p-0 text-center  btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                    Edit account
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                                        <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                                    </svg>
                                </button>
                                <?php
                                # ACCOUNT DELETE
                                if (isset($_POST['ConfirmDelete'])) {
                                    $query = "DELETE FROM movies WHERE userID =" . $_SESSION['$userID'];
                                    $row = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        printf("Error: %s\n", mysqli_error($dbc));
                                        exit();
                                    }
                                    $query = "DELETE FROM review WHERE userID =" . $_SESSION['$userID'];
                                    $row = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        printf("Error: %s\n", mysqli_error($dbc));
                                        exit();
                                    }
                                    $query = "DELETE FROM tv_show WHERE userID =" . $_SESSION['$userID'];
                                    $row = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        printf("Error: %s\n", mysqli_error($dbc));
                                        exit();
                                    }
                                    $query = "DELETE FROM user WHERE id =" . $_SESSION['$userID'];
                                    $row = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        printf("Error: %s\n", mysqli_error($dbc));
                                        exit();
                                    }
                                    session_destroy();
                                    echo "<script> location.href='index.php'; </script>";
                                    exit;
                                }
                                ?>
                                <button type="button" name="delete" class="icon-link btn link-danger text-center text-decoration-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">
                                    Delete account
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-dash" viewBox="0 0 16 16">
                                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1Zm0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path>
                                        <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <!-- Modal for icon -->
        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Choose new profile icon</h1>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="IconForm" enctype="multipart/form-data" class="needs-validation">
                            <div class="avatars row mb-4">
                                <div id="invalidText" class="mb-2">Need to select your profile icon</div>
                                <?php
                                $query = "SELECT * FROM profile_icon";
                                $result = mysqli_query($dbc, $query);
                                if (!$result) {
                                    printf("Error: %s\n", mysqli_error($dbc));
                                    exit();
                                }
                                $row = mysqli_fetch_array($result);
                                mysqli_data_seek($result, 0);
                                while ($row = mysqli_fetch_array($result)) {
                                    // In advance checked profile icon
                                    if ($avatarID !== $row['id']) {
                                        $imageSrc = 'data:image/png;base64,' . base64_encode($row['image']);
                                        print '
                                        <div class="col-md-4 col-sm-4  mb-4">                                    
                                            <input type="radio" class="form-check-input visually-hidden" name="avatar" id="' . $row['name'] . '" value="' . $row['id'] . '">
                                            <label for="' . $row['name'] . '">
                                                <img src="' . $imageSrc . '" alt="' . $row['name'] . '" class="form-check-label rounded">
                                            </label>    
                                        </div>
                                        ';
                                    }
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submitIcon" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const newelem = document.getElementById("IconForm");
                newelem.addEventListener('submit', function(eventi) {
                    document.getElementById("IconForm").classList.add("needs-validation");
                    document.getElementById("AccountForm").classList.remove("needs-validation");
                    const radioButtons = document.querySelectorAll('input[type="radio"]');
                    var isFormValid = true;

                    // Is profile icon selected
                    for (let i = 0; i < radioButtons.length; i++) {
                        if (radioButtons[i].checked) {
                            radioButtons[i].classList.remove('is-invalid');
                            document.getElementById("invalidText").style.display = "none";
                            isFormValid = true;
                            break;
                        } else {
                            radioButtons[i].classList.add('is-invalid');
                            document.getElementById("invalidText").style.display = "block";
                            isFormValid = false;
                        }
                    }
                    if (!isFormValid) {
                        eventi.preventDefault();
                        eventi.stopPropagation();
                    }
                }, false);
            </script>
        </div>



        <!-- Modal for account name -->
        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Choose new profile icon</h1>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="AccountForm" enctype="multipart/form-data" class="needs-validation">
                            <div class="row mb-4 form-floating">
                                <input type="text" name="name" class="form-control" id="floatingInput1" placeholder="Enter your name">
                                <label for="floatingInput1" class="form-label" id="label1">Input your name</label>
                                <div class="invalid-feedback">
                                    Please write a name.
                                </div>
                            </div>
                            <div class="row mb-4 form-floating">
                                <input type="text" name="surname" class="form-control" id="floatingInput2" placeholder="Enter your surname">
                                <label for="floatingInput2" class="form-label" id="label2">Input your surname</label>
                                <div class="invalid-feedback">
                                    Please write a surname.
                                </div>
                            </div>
                            <div class="row mb-4 form-floating">
                                <input type="text" name="username" class="form-control" id="floatingInput3" placeholder="Enter your username">
                                <label for="floatingInput3" class="form-label" id="label3">Input your username</label>
                                <div class="invalid-feedback">
                                    Please write a username.
                                </div>
                                <?php
                                if ($userExist == true) {
                                    print '
                                <script>                                    
                                    document.getElementById("floatingInput3").classList.add("is-invalid");
                                </script>';
                                    print '<div class="invalid-feedback">' . $message . '</div>';
                                }
                                ?>
                            </div>
                            <div class="row mb-4 form-floating">
                                <input type="password" name="password" class="form-control" id="floatingPassword1" placeholder="Enter your password" aria-describedby="passwordHelpBlock">
                                <label for="floatingPassword1" class="form-label" id="label4">Input your password</label>
                                <div id="passwordHelpBlock" class="form-text">
                                    Your password must be 6-14 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submitAccount" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const forms2 = document.querySelectorAll('.needs-validation');
                const AccElem = document.getElementById("AccountForm");
                document.getElementById("AccountForm").classList.add("needs-validation");
                var passwordPattern = /^(?=.*[0-9])(?=.*[a-zA-Z])(?!.*\s)(?!.*[\u{1F600}-\u{1F64F}])[\w\W]{6,14}$/u;

                AccElem.addEventListener('submit', function(event) {
                    document.getElementById("IconForm").classList.remove("needs-validation");
                    var InputName = document.getElementById("floatingInput1");
                    var InputSurname = document.getElementById("floatingInput2");
                    var InputUsername = document.getElementById("floatingInput3");
                    var InputPassword = document.getElementById("floatingPassword1");
                    var inputs = [InputName, InputSurname, InputUsername];
                    var isFormValid = true;

                    // is every input filled and not empty
                    inputs.forEach(function(input) {
                        if (!input.value) {
                            input.classList.add('is-invalid');
                            isFormValid = false;
                        } else {
                            if (/\s/.test(input.value)) {
                                input.classList.add('is-invalid');
                                isFormValid = false;
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        }
                    });

                    // Check if password is correct
                    if (!passwordPattern.test(InputPassword.value)) {
                        InputPassword.classList.add("is-invalid");
                        document.getElementById("passwordHelpBlock").classList.add("invalid-feedback");
                        isFormValid = false;
                    } else {
                        InputPassword.classList.remove("is-invalid");
                        document.getElementById("passwordHelpBlock").classList.remove("invalid-feedback");
                    }


                    if (!isFormValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                }, false);
            </script>
        </div>


        <!-- Modal account delete-->

        <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure?</h1>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="DeleteAccount" enctype="multipart/form-data">
                            <p>Do you really want to delete these records? This process cannot be undone</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <button type="submit" name="ConfirmDelete" class="btn btn-danger">Yes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <main>
            <!-- My Reviews -->
            <section class="container-fluid" id="Review">
                <!-- Section title  -->
                <div class="container">
                    <div class="row Section-Title">
                        <h3 class="col-md-3 col-sm-6">My reviews</h3>
                        <span class="col-md-7 col-sm-6 .d-md-flex divider"></span>
                    </div>
                </div>

                <!-- Individual article section  -->

                <?php

                $query = "SELECT * FROM review WHERE userID = '$userID' ORDER BY review.id DESC";
                $result = mysqli_query($dbc, $query);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }
                if (mysqli_num_rows($result) === 0) {
                    print '
                        <div class="container">
                            <div class="row justify-content-around">
                                 <p class="text-center fst-italic text-secondary  no-content">None content exist here</p>
                            </div>
                        </div>  
                ';
                } else {
                    while ($row = mysqli_fetch_array($result)) {



                        print '
                <div class="container">
                    <div class="row justify-content-around">
                        <div class="ReviewRow col-md-12">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a>
                                            <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />                                        
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="container">
                                            <div class="row justify-content-start">
                                                <div class="post-meta col-md-12 my-2">
                                                    <span class="post-author">
                                                        <span class="RCategory">
                                                            <a>review</a>                                                            
                                                        </span>
                                                    </span> |
                                                    <span class="meta-date">                                                        
                                                        <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <h2 class="post-title">                                                    
                                                    <a>';
                        if (strlen($row['podnaslov']) > 90) {
                            echo $row['naslov'] . ' - ' . substr($row['podnaslov'], 0, 90) . '...';
                        } else {
                            echo $row['naslov'] . ' - ' . substr($row['podnaslov'], 0, 90);
                        }
                        print '
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="post-description">                                                    
                                                    <p>';
                        if (strlen($row['sazetak']) > 140) {
                            echo substr($row['sazetak'], 0, 140) . '...';
                        } else {
                            print $row['sazetak'];
                        }
                        print '
                                                    </p>
                                                </div>
                                            </div>                                            
                                            <div class="row justify-content-start">
                                                <div class="d-flex">
                                                    
                                                    <a href="edit.php?Category=Review&id=' . $row['id'] . '&userID=' . $row['userID'] . '" class="me-4 link-primary text-decoration-none">Edit this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" onclick="deleteData(\'' . $row['id'] . '\', \'review\')" class="link-danger text-decoration-none">Delete this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                        </svg>
                                                    </a>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                    }
                }
                ?>
            </section>

            <!-- My Movie articles -->
            <section class="container-fluid" id="Review">
                <!-- Section title  -->
                <div class="container">
                    <div class="row Section-Title">
                        <h3 class="col-md-4 col-sm-8">My Movie articles</h3>
                        <span class="col-md-6 col-sm-4 .d-md-flex divider"></span>
                    </div>
                </div>

                <!-- Individual article section  -->

                <?php

                $query = "SELECT * FROM movies WHERE userID = '$userID' ORDER BY movies.id DESC";
                $result = mysqli_query($dbc, $query);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }
                if (mysqli_num_rows($result) === 0) {
                    print '
                        <div class="container">
                            <div class="row justify-content-around">
                                 <p class="text-center fst-italic text-secondary  no-content">None content exist here</p>
                            </div>
                        </div>  
                ';
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        print '
                <div class="container">
                    <div class="row justify-content-around">
                        <div class="ReviewRow col-md-12">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a>
                                            <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />                                        
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="container">
                                            <div class="row justify-content-start">
                                                <div class="post-meta col-md-12 my-2">
                                                    <span class="post-author">
                                                        <span class="RCategory">
                                                            <a>movie</a>                                                            
                                                        </span>
                                                    </span> |
                                                    <span class="meta-date">                                                        
                                                        <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <h2 class="post-title">                                                    
                                                    <a>';
                        if (strlen($row['naslov']) > 90) {
                            echo substr($row['naslov'], 0, 90) . '...';
                        } else {
                            print $row['naslov'];
                        }
                        print '
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="post-description">                                                    
                                                    <p>';
                        if (strlen($row['sazetak']) > 140) {
                            echo substr($row['sazetak'], 0, 140) . '...';
                        } else {
                            print $row['sazetak'];
                        }
                        print '
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="d-flex">
                                                    <a href="edit.php?Category=Film&id=' . $row["id"] . '&userID=' . $row["userID"] . '" class="me-4 link-primary text-decoration-none">Edit this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" onclick="deleteData(\'' . $row['id'] . '\', \'movies\')" class="link-danger text-decoration-none">Delete this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                    }
                }
                ?>
            </section>

            <!-- My TV articles -->
            <section class="container-fluid" id="Review">
                <!-- Section title  -->
                <div class="container">
                    <div class="row Section-Title">
                        <h3 class="col-md-3 col-sm-8">My TV articles</h3>
                        <span class="col-md-7 col-sm-4 .d-md-flex divider"></span>
                    </div>
                </div>

                <!-- Individual article section  -->

                <?php

                $query = "SELECT * FROM tv_show WHERE userID = '$userID' ORDER BY tv_show.id DESC";
                $result = mysqli_query($dbc, $query);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($dbc));
                    exit();
                }
                if (mysqli_num_rows($result) === 0) {
                    print '
                        <div class="container">
                            <div class="row justify-content-around">
                                 <p class="text-center fst-italic text-secondary no-content">None content exist here</p>
                            </div>
                        </div>  
                ';
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        print '
                <div class="container">
                    <div class="row justify-content-around">
                        <div class="ReviewRow col-md-12">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a>
                                            <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />                                        
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="container">
                                            <div class="row justify-content-start">
                                                <div class="post-meta col-md-12 my-2">
                                                    <span class="post-author">
                                                        <span class="RCategory">
                                                            <a>tv</a>                                                            
                                                        </span>
                                                    </span> |
                                                    <span class="meta-date">                                                        
                                                        <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <h2 class="post-title">                                                    
                                                    <a>';
                        if (strlen($row['naslov']) > 90) {
                            echo substr($row['naslov'], 0, 90) . '...';
                        } else {
                            print $row['naslov'];
                        }
                        print '
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="post-description">                                                    
                                                    <p>';
                        if (strlen($row['sazetak']) > 140) {
                            echo substr($row['sazetak'], 0, 140) . '...';
                        } else {
                            print $row['sazetak'];
                        }
                        print '
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="d-flex">
                                                    <a href="edit.php?Category=Tv&id=' . $row["id"] . '&userID=' . $row["userID"] . '" class="me-4 link-primary text-decoration-none">Edit this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" onclick="deleteData(\'' . $row['id'] . '\', \'tv_show\')" class="link-danger text-decoration-none">Delete this
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                    }
                }
                ?>


            </section>

            <!-- Delete Article -->
            <script>
                function deleteData(id, table) {
                    $.ajax({
                        url: 'delete.php',
                        type: 'POST',
                        data: {
                            id: id,
                            table: table
                        },
                        success: function(response) {
                            if (response == 1) {
                                location.reload();
                            } else {
                                alert('Error deleting record');
                            }
                        }
                    });
                }
            </script>
        </main>

        <footer>
            <div class="container">
                <div class="row d-flex flex-wrap justify-content-between py-2 my-3 border-top">
                    <div class="col-md-6 d-flex align-items-center py-4">
                        <a href="mailto: dgluhak1@tvz.hr" class="mb-3 me-2 mb-md-0 text-decoration-none lh-1 text-body-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                            </svg>
                        </a>
                        <span class="mx-2 mb-3 mb-md-0 text-body-seondary">@ Daniel Gluhak. All Rights Reserved, TVZ 2023</span>
                    </div>
                    <ul class="nav col-md-4 py-4 justify-content-end list-unstyled d-flex">
                        <li class="ms-3">
                            <a href="https://github.com/Trilantini/Napredne-tehnike-programiranja-web-servisa" target="_blank" class="text-body-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>

        <button class="scrollToTopBtn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6">
                <path d="M12 6H0l6-6z" />
            </svg>
        </button>
        <script>
            //Script to determine when will the button be visible
            var scrollToTopBtn = document.querySelector(".scrollToTopBtn");
            var rootElement = document.documentElement;

            function handleScroll() {
                var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
                if (rootElement.scrollTop / scrollTotal > 0.6) {
                    // Show
                    scrollToTopBtn.classList.add("showBtn");
                } else {
                    // Hide
                    scrollToTopBtn.classList.remove("showBtn");
                }
            }

            function scrollToTop() {
                rootElement.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
            scrollToTopBtn.addEventListener("click", scrollToTop);
            document.addEventListener("scroll", handleScroll);

            //Easter egg in top bar
            var content = document.getElementById("logo");
            var rot = 360;
            content.addEventListener("click", function() {
                content.style = 'transform: rotate(' + rot + 'deg)';
                rot += 360;
            });
        </script>
    <?php
    }
    ?>
</body>

</html>