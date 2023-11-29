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

    <!-- Google Font Roboto-->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />
    <!-- Font Awesome free icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Outside css-->
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>" />

    <title>FilmGeekCentral | Register yourself</title>
</head>

<body>
    <?php
    include "connect.php";
    session_start();
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
            </ul>
        </div>
    </div>

    <?php
    $RegisterUser = false;
    $userExist = false;

    if (isset($_POST['Register'])) {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $profileIcon = $_POST['avatar'];
        $PasswordHash = password_hash($password, CRYPT_BLOWFISH);
        $sql = "SELECT username FROM user WHERE username=?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "User already exists!";
            $userExist = true;
        } else {
            $sql = "INSERT INTO user (name,surname,username,password,avatar) VALUES (?,?,?,?,?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssi', $name, $surname, $username, $PasswordHash, $profileIcon);
                mysqli_stmt_execute($stmt);
                $RegisterUser = true;
            }
        }
        mysqli_close($dbc);
    }

    ?>
    <main class="form py-2">
        <section class="container ">
            <?php
            if ($RegisterUser == true) {
                print '
                <script>
                    alert("Account has been successfully created");
                    window.location.href="administration.php";
                </script>';
                exit;
            } else {
            ?>
                <div class="row justify-content-evenly py-3">
                    <div class="col-md-2 go-back">
                        <button class="btn btn-link" onclick="location.href='administration.php'" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                            </svg>
                            GO BACK</button>
                    </div>

                    <h3 class="col-md-5 px-1">Sign up Form</h3>
                </div>

                <div class="row justify-content-center">
                    <form action="" method="post" enctype="multipart/form-data" class="Registration needs-validation col-md-4">
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

                        <div class="avatars row mb-4">
                            <legend>Select your profile icon:</legend>
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
                            ?>

                        </div>

                        <div class="row mb-4 col-md-5 button">
                            <button class="btn py-2 text-center btn-primary" type="submit" name="Register">Create your account</button>
                        </div>
                    </form>
                </div>

                <!-- Validation -->
                <script>
                    const forms = document.querySelectorAll('.needs-validation')
                    var passwordPattern = /^(?=.*[0-9])(?=.*[a-zA-Z])(?!.*\s)(?!.*[\u{1F600}-\u{1F64F}])[\w\W]{6,14}$/u;
                    Array.from(forms).forEach(form => {
                        form.addEventListener('submit', function(event) {
                            var InputName = document.getElementById("floatingInput1");
                            var InputSurname = document.getElementById("floatingInput2");
                            var InputUsername = document.getElementById("floatingInput3");
                            var InputPassword = document.getElementById("floatingPassword1");
                            const radioButtons = document.querySelectorAll('input[type="radio"]');
                            var inputs = [InputName, InputSurname, InputUsername];
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
                    });
                </script>
            <?php
            }
            ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row d-flex flex-wrap justify-content-between border-top">
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


    <script>
        //Easter egg in top bar
        var content = document.getElementById("logo");
        var rot = 360;
        content.addEventListener("click", function() {
            content.style = 'transform: rotate(' + rot + 'deg)';
            rot += 360;
        });
    </script>
</body>

</html>