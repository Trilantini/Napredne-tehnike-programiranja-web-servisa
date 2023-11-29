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
    <title>
        <?php
        function send($ArticleTitle, $ArticleSubtitle)
        {
            echo "<script>document.title = '$ArticleTitle - $ArticleSubtitle | FilmGeekCentral';</script>";
        };

        function send2($ArticleTitle)
        {
            echo "<script>document.title = '$ArticleTitle | FilmGeekCentral';</script>";
        };
        ?>
    </title>
</head>

<body>
    <?php
    include "connect.php";
    define('IMGPATH', 'img/');
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
                  <a id="MainPage" class"link-light">My profile</a>
                  <div class="dropdown-content">
                    <a href="profile.php">Account</a>
                    <a href="create.php">Creat Article</a>
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
    <?php
    if ((isset($_GET['id']) && isset($_GET['Category'])) && (isset($_GET['id']) != "" && isset($_GET['Category']) != "")) {
        $kategorija = mysqli_real_escape_string($dbc, $_GET['Category']);
        $SubID = mysqli_real_escape_string($dbc, $_GET['id']);

        if ($kategorija == "Film") {
            $query = "SELECT * FROM movies WHERE  id=" . $SubID;
        } elseif ($kategorija == 'Tv') {
            $query = "SELECT * FROM tv_show WHERE  id=" . $SubID;
        } elseif ($kategorija == 'Review') {
            $query = "SELECT * FROM review WHERE  id=" . $SubID;
        }

        $result = mysqli_query($dbc, $query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
        }

        $row = mysqli_fetch_array($result);

        if ($kategorija == 'Review') {
            send($row['naslov'], $row['podnaslov']);
        } else {
            send2($row['naslov']);
        }



        print '
                   <!-- Header with image -->
                    <header class="container header-info py-3">
                        <div class="row mx-4">
                            <div class="Media-content col-md-12">
                                <div class="media">
                                    <a href="#" class="image-link">
                                        <img class="post-image" src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />                                       
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="content">
                                        <div class="post-meta">
                                            <h2 class="post-meta-title">';
        echo '<a href="#">' . $row['naslov'] . ($kategorija == 'Review' ? ' - ' . $row['podnaslov'] : '') . '</a>';
        print '                                              
                                            </h2>
                                            <div class="meta-below d-inline-flex gap-1">
                                                <span class="post-author">
                                                    <span class="by">By</span>
                                                    <span class="Author">' . $row['autor'] . '</span>
                                                </span>
                                                |
                                                <span class="meta-date">
                                                    <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                                </span>';
        if ($kategorija == 'Review') {
            echo '<span class="ms-5">Where to watch (US): ';

            $url = 'https://www.omdbapi.com/?apikey=be5ea402&t=' . $row['naslov'];
            $json = file_get_contents($url);
            $_data = json_decode($json, true);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.watchmode.com/v1/title/' . $_data['imdbID'] . '/sources/?apiKey=S8iomXhnNkQNhNrBIUrbf1kCsqwY9LqbVvXnvaKe&&region=US');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($response);
            $names = [];

            foreach ($json as $object) {
                if (property_exists($object, 'name')) {
                    $names[] = $object->name;
                }
            }
            if (in_array('netflix', array_map('strtolower', $names))) {
                print 'Netflix';
            } elseif (in_array('disney+', array_map('strtolower', $names))) {
                print 'Disney+';
            } elseif (in_array('max', array_map('strtolower', $names)) || in_array('hbo max', array_map('strtolower', $names))) {
                print 'HBO MAX';
            } elseif (in_array('appletv+', array_map('strtolower', $names))) {
                print 'Apple TV+';
            } elseif (in_array('paramount+', array_map('strtolower', $names))) {
                print 'Paramount+';
            } elseif (in_array('crunchyroll', array_map('strtolower', $names))) {
                print 'Crunchyroll';
            } elseif (in_array('adult swim', array_map('strtolower', $names))) {
                print 'Adult Swim';
            } elseif (in_array('prime video', array_map('strtolower', $names))) {
                print 'Prime Video';
            } else {
                print 'as of right now, nowhere';
            }
        }
        print '</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>                        
            ';

        print '
            <main>
                <!-- Movies News -->
                <section class="container">
                    <div class="row justify-content-start article-text py-5">
                        <p>' . $row['tekst'] . '</p>';
        if ($kategorija == 'Review') {
            print '
                            <div class="content-rating py-3">
                                <h3>
                                    Our Rating:
                                </h3>
                                <span class="py-2">';
            $i = 1;
            while ($i <= $row['ocjena']) {
                print '
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    ';
                $i++;
            }
            print '   
                                </span>
                            </div>
                            ';
        }
        print '    
                    </div>
                </section>
            </main>

            ';
    }
    ?>



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
            if (rootElement.scrollTop / scrollTotal > 0.8) {
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
                behavior: "smooth",
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
</body>

</html>