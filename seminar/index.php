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

  <title>FilmGeekCentral | Home</title>
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
          <a id="MainPage">Home</a>
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

  <!-- Header with image -->
  <header id="hero-image" class="container-fluid">
    <h1>Welcome to <strong>FilmGeekCentral</strong></h1>
    <p>
      Prepare to be captivated, informed, and inspired as you enter Film Geek
      Central, where the rhythm of entertainment never falters.
    </p>
  </header>

  <main>
    <!-- Movies News -->
    <section class="container-fluid" id="Movie">
      <div class="container">
        <div class="row Section-Title justify-content-around">
          <h3 class="col-md-1 col-sm-1 col-xs-1">FILM</h3>
          <span class="col-md-8 col-sm-3 .d-md-flex divider"></span>
          <a href="category.php?id=Film" class="col-lg-1 col-sm-3" id="view-link">View More</a>
        </div>
      </div>
      <div class="container">
        <div class="row justify-content-around">
          <?php
          $query = "SELECT * FROM movies ORDER BY id DESC LIMIT 3";
          $result = mysqli_query($dbc, $query);
          if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
          }
          $row = mysqli_fetch_array($result);
          print '
                  <div class="Main-article col-md-7 mb-5">
                    <div class="Media">
                      <a href="article.php?Category=Film&id=' . $row['id'] . '">
                        <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />
                      </a>
                    </div>
                    <div class="Content">
                      <h2 class="post-title">
                        <a href="article.php?Category=Film&id=' . $row['id'] . '">';
          if (strlen($row['naslov']) > 76) {
            echo substr($row['naslov'], 0, 76) . '...';
          } else {
            print $row['naslov'];
          }
          print '
                        </a>
                      </h2>
                      <div class="post-meta">
                        <span class="post-author">
                          <span class="by">By</span>
                          <span class="Author">' . $row['autor'] . '</span>
                        </span>
                        <span class="meta-date">
                          <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                        </span>
                      </div>
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
                      <a href="article.php?Category=Film&id=' . $row['id'] . '" class="ReadMore">Read More</a>
                    </div>
                  </div>

                  <div class="Secondary-article col-md-4">
                      <div class="container">';
          while ($row = mysqli_fetch_array($result)) {
            print '<div class="row">
                                  <div class="col-sm-12">
                                    <div class="Media">
                                      <a href="article.php?Category=Film&id=' . $row['id'] . '">
                                        <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />
                                      </a>
                                    </div>
                                    <div class="Content">
                                      <h2 class="post-title">
                                        <a href="article.php?Category=Film&id=' . $row['id'] . '">';
            if (strlen($row['naslov']) > 76) {
              echo substr($row['naslov'], 0, 76) . '...';
            } else {
              print $row['naslov'];
            }
            print '
                                        </a>
                                      </h2>
                                      <div class="post-meta">
                                        <span class="post-author">
                                          <span class="by">By</span>
                                          <span class="Author">' . $row['autor'] . '</span>
                                        </span>
                                        <span class="meta-date">
                                          <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
          }
          print '</div>
                  </div>';
          ?>
        </div>
      </div>
    </section>


    <!-- Main Carousel-->
    <div id="carouselExampleCaptions" data-bs-ride="carousel" class="carousel slide">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <?php
        $sql = "SELECT * FROM movies ORDER BY RAND() LIMIT 3";
        $result = mysqli_query($dbc, $sql) or die("Error:" . mysqli_error($dbc));
        $image_count = 0;
        while ($rows = mysqli_fetch_assoc($result)) {
          $active_class = "";
          if (!$image_count) {
            $active_class = 'active';
            $image_count = 1;
          }
          $image_count++;
          print '
              <div class="carousel-item ' . $active_class . '">
                <a href="article.php?Category=Film&id=' . $rows['id'] . '">
                  <img src="' . IMGPATH . $rows['slika'] . '" class="d-block w-100" alt="' . $rows['naslov'] . '" />
                  <div class="carousel-caption d-none d-md-block">      
                    <h4>' . $rows['naslov'] . '</h4>
                    <p>' . $rows['sazetak'] . '</p>        
                  </div>                          
                </a>          
              </div>
          ';
        }
        ?>
      </div>
    </div>

    <!-- Tv Show News -->
    <section class="container-fluid" id="Tv">
      <div class="container">
        <div class="row Section-Title justify-content-around">
          <h3 class="col-md-1 col-sm-1">TV</h3>
          <span class="col-md-8 col-sm-6 .d-md-flex divider"></span>
          <a href="category.php?id=Tv" class="col-md-1 col-sm-3" id="view-link">View More</a>
        </div>
      </div>
      <div class="container">
        <div class="row justify-content-around">
          <?php
          $query = "SELECT * FROM tv_show ORDER BY id DESC LIMIT 3";
          $result = mysqli_query($dbc, $query);
          if (!$result) {
            printf("Error: %s\n", mysqli_error($dbc));
            exit();
          }
          $row = mysqli_fetch_array($result);

          print '
                  <div class="Main-article col-md-7 mb-5">
                    <div class="Media">
                      <a href="article.php?Category=Tv&id=' . $row['id'] . '">
                        <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />
                      </a>
                    </div>
                    <div class="Content">
                      <h2 class="post-title">
                        <a href="article.php?Category=Tv&id=' . $row['id'] . '">';
          if (strlen($row['naslov']) > 76) {
            echo substr($row['naslov'], 0, 76) . '...';
          } else {
            print $row['naslov'];
          }
          print '
                        </a>
                      </h2>
                      <div class="post-meta">
                        <span class="post-author">
                          <span class="by">By</span>
                          <span class="Author">' . $row['autor'] . '</span>
                        </span>
                        <span class="meta-date">
                          <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                        </span>
                      </div>
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
                      <a href="article.php?Category=Tv&id=' . $row['id'] . '" class="ReadMore">Read More</a>
                    </div>
                  </div>

                  <div class="Secondary-article col-md-4">
                      <div class="container">';
          while ($row = mysqli_fetch_array($result)) {
            print '<div class="row">
                                  <div class="col-sm-12">
                                    <div class="Media">
                                      <a href="article.php?Category=Tv&id=' . $row['id'] . '">
                                        <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />
                                      </a>
                                    </div>
                                    <div class="Content">
                                      <h2 class="post-title">
                                        <a href="article.php?Category=Tv&id=' . $row['id'] . '">';
            if (strlen($row['naslov']) > 76) {
              echo substr($row['naslov'], 0, 76) . '...';
            } else {
              print $row['naslov'];
            }
            print '
                                        </a>
                                      </h2>
                                      <div class="post-meta">
                                        <span class="post-author">
                                          <span class="by">By</span>
                                          <span class="Author">' . $row['autor'] . '</span>
                                        </span>
                                        <span class="meta-date">
                                          <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
          }
          print '</div>
                  </div>';
          ?>
        </div>
      </div>
    </section>

    <!-- Latest Review Section -->
    <section class="container-fluid" id="Review">
      <!-- Section title  -->
      <div class="container">
        <div class="row Section-Title">
          <h3 class="col-md-3 col-sm-6">Latest reviews</h3>
          <span class="col-md-7 col-sm-6 .d-md-flex divider"></span>
        </div>
      </div>
      <!-- Individual article section  -->
      <?php
      $query = 'SELECT * FROM review ORDER BY id DESC LIMIT 3';
      $result = mysqli_query($dbc, $query);
      if (!$result) {
        printf("Error: %s\n", mysqli_error($dbc));
        exit();
      }
      while ($row = mysqli_fetch_array($result)) {
        print '<div class="container">
               <div class="row justify-content-around">
                 <div class="ReviewRow col-md-12">
                   <div class="container">
                     <div class="row">
                       <div class="col-md-4">
                         <a href="article.php?Category=Review&id=' . $row['id'] . '">
                          <img src="' . IMGPATH . $row['slika'] . '" alt="' . $row['naslov'] . '" srcset="" />
                         </a>
                       </div>
                       <div class="col-md-8">
                         <div class="container">
                           <div class="row justify-content-start">
                             <div class="post-meta col-md-12 my-2">
                               <span class="post-author">
                                 <span class="RCategory">
                                   <a href="category.php?id=' . $row['kategorija'] . '">' . $row['kategorija'] . '</a>
                                 </span>
                               </span>
                               |
                               <span class="meta-date">
                                 <time class="post-date">' . date('F d, Y', strtotime($row['datum'])) . '</time>
                               </span>
                             </div>
                           </div>
                           <div class="row justify-content-start">
                             <h2 class="post-title">
                               <a href="article.php?Category=Review&id=' . $row['id'] . '">';
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
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>';
      }
      ?>
      <!-- Additional button  -->
      <div class="container">
        <div class="row justify-content-center">
          <a href="reviews.php" class="view-additional-link col-md-4">
            <p>View additional reviews</p>
          </a>
        </div>
      </div>
    </section>
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
</body>

</html>