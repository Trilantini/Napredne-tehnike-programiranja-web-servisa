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

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/jombkuxit0a1a5diq2dyskehe022sxvi4ck1p5zup0ncvfqr/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            menubar: false,
            branding: false,
            elementpath: false,
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });

            },
            forced_root_block: "",
            plugins: ['advlist', 'lists', 'powerpaste'],
            toolbar: 'undo redo |a11ycheck casechange blocks fontsize| bold italic underline strikethrough subscript superscript forecolor removeformat  | bullist numlist | alignleft aligncenter alignright alignjustify lineheight outdent indent'
        })
    </script>

    <!--Outside css-->
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>" />

    <title>FilmGeekCentral | Create your own article</title>
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
                <?php
                if (isset($_SESSION['$username']) && isset($_SESSION['$logedin'])) {
                    print '
                <li class="nav-item dropdown">
                  <a id="MainPage" class="link-light">My profile</a>
                  <div class="dropdown-content">
                    <a href="profile.php">Account</a>
                    <a id="MainPage">Create Article</a>
                    <a href="signout.php">Log out</a>
                  </div>
                </li>
          ';
                } else {
                    print '
          <li class="nav-item">
            <a href="administration.php">Sign In</a>
          </li>
          ';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Header -->
    <div class="container">
        <header class="d-flex py-3 mb-4 border-bottom">
            <div class="col-md-12 py-2">
                <h1 class="d-inline-flex m-0">Create your own article</h1>
            </div>
        </header>
    </div>


    <?php
    if (isset($_POST['PostArticle'])) {
        $ClientId = $_SESSION['$userID'];
        $query = "SELECT name, surname FROM user WHERE id = $ClientId";
        $result = mysqli_query($dbc, $query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
        }
        $row = mysqli_fetch_array($result);
        $author = $row['name'] . ' ' . $row['surname'];
        $date = date("Y-m-d");
        $title = $_POST["Title"];
        $subtitle = $_POST["SubTitle"];
        $abstract = $_POST["Abstract"];
        $content  = $_POST["Article"];
        $picturename = $_FILES["thumbnail"]['name'];
        $pic_path = "img/" . $picturename;
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $pic_path);
        $category = $_POST["Category"];
        if ($category == "Tv" || $category == "Film") {
            $rating = $_POST['star'];
            $sql = "INSERT INTO review(datum,autor,naslov,podnaslov,sazetak,tekst,kategorija,ocjena,slika,userID)
            VALUES ('$date','$author','$title','$subtitle','$abstract','$content','$category','$rating','$picturename','$ClientId')";
        } else {
            $title = $title . " - " . $subtitle;
            $sql = "INSERT INTO $category(datum,autor,naslov,sazetak,tekst,slika,userID)
            VALUES ('$date','$author','$title','$abstract','$content','$picturename','$ClientId')";
        }

        $result = mysqli_query($dbc, $sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
        }
        mysqli_close($dbc);
        echo "<script> location.href='profile.php'; </script>";
        die();
    }
    ?>


    <main class="container py-2">
        <div class="justify-content-between">
            <form action="" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
                <div class="col-5">
                    <label for="inputTitle" class="form-label">Title (From Tv Show or Movie if it's a review)</label>
                    <input type="text" name="Title" maxlength="100" class="form-control" id="inputTitle" aria-describedby="titleHelp">
                    <div id="titleHelp" class="form-text">Maximum length is 100 characters</div>
                    <div class="invalid-feedback">
                        Please enter the TV Show or Movie title.
                    </div>
                </div>
                <div class="col-2 position-relative">
                    <p class="position-absolute top-50 start-50 translate-middle user-select-none">–</p>
                </div>
                <div class="col-5">
                    <label for="inputTitle" class="form-label">Sub Title</label>
                    <input type="text" name="SubTitle" maxlength="100" class="form-control" id="inputSubTitle" aria-describedby="SubtitleHelp">
                    <div id="SubtitleHelp" class="form-text">Maximum length is 100 characters</div>
                    <div class="invalid-feedback">
                        Please enter the some text here.
                    </div>
                </div>
                <div class="col-12">
                    <label for="inputAbstract" class="form-label">Abstract</label>
                    <input type="text" name="Abstract" maxlength="160" class="form-control" id="inputAbstract" aria-describedby="abstractHelp">
                    <div id="abstractHelp" class="form-text">Maximum length is 160 characters</div>
                    <div class="invalid-feedback">
                        Please write short abstract.
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="inputCategory" class="form-label">Category</label>
                    <select id="inputCategory" name="Category" class="form-select" onchange="checkCategory(this)">
                        <option value="" selected>Choose...</option>
                        <option value="movies">Film</option>
                        <option value="tv_show">Tv</option>
                        <option value="Film">Review Movie</option>
                        <option value="Tv">Review Show</option>
                    </select>
                    <div class="invalid-feedback">
                        Please choose one of categories.
                    </div>
                </div>
                <div id="Review-stars" class="d-none col-md-3 px-4">
                    <label for="inputRating" class="form-label">Rating</label>
                    <div class="d-flex">
                        <div class="stars d-inline">
                            <?php
                            for ($i = 5; $i >= 1; $i--) {
                                print '
                                        <input class="star star-' . $i . '" id="star-' . $i . '" type="radio" name="star" value="' . $i . '" />
                                        <label class="star star-' . $i . '" for="star-' . $i . '"></label>             
                                        ';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        Please choose rating from 1 to 5.
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="Pictures" class="form-label">Picture</label>
                    <input type="file" class="form-control" id="Pictures" name="thumbnail" accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.tif,.tiff,.eps,.raw" aria-label="file example" aria-describedby="ImageHelp" />
                    <div id="ImageHelp" class="form-text">Accepted files: jpg, png, bmp, tif, tiff, gif, eps and raw</div>
                    <div class="invalid-feedback">
                        Please choose one picture.
                    </div>
                </div>
                <div class="col-12">
                    <label for="Content" class="form-label">Write Your Article</label>
                    <div id="articleHelp" class="form-text">Avoid using apostrophe</div>
                    <textarea name="Article" id="Content" pattern="[^':]*$" class="form-control" aria-describedby="articleHelp"></textarea>
                    <div class="invalid-feedback">
                        Article can't be empty.
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn px-5 py-2 btn-primary" name="PostArticle">Post</button>
                </div>
            </form>
        </div>
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


    <script>
        function checkCategory(that) {
            if (that.value == "Tv" || that.value == "Film") {
                document.getElementById("Review-stars").classList.remove("d-none");
            } else {
                document.getElementById("Review-stars").classList.add("d-none");
                document.querySelector('input[name="star"]:checked').checked = false;
            }
        }


        //Validation
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                var InputTitle = document.getElementById("inputTitle");
                var InputSubTitle = document.getElementById("inputSubTitle");
                var InputAbstract = document.getElementById("inputAbstract");
                var InputCategory = document.getElementById("inputCategory");
                var InputContent = document.getElementById("Content");
                var InputPicture = document.getElementById("Pictures");
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.tif|\.tiff|\.eps|\.raw)$/i;
                const radioButtons = document.querySelectorAll('input[type="radio"]');
                var inputs = [InputTitle, InputSubTitle, InputAbstract, InputContent, InputCategory, InputPicture];
                var isFormValid = true;

                // is every input filled
                inputs.forEach(function(input) {
                    if (!input.value) {
                        input.classList.add('is-invalid');
                        isFormValid = false;
                    } else {
                        // If Reviw selected, check rating
                        if (InputCategory.value == "Tv" || InputCategory.value == "Film") {
                            if (!Array.from(radioButtons).some(radio => radio.checked)) {
                                InputCategory.classList.add('is-invalid');
                                isFormValid = false;
                            }
                        }

                        // Check if file format is correct
                        if (!allowedExtensions.exec(InputPicture.value)) {
                            InputPicture.classList.add('is-invalid');
                            isFormValid = false;
                        }
                    }

                });
                if (!isFormValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            }, false);
        });



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