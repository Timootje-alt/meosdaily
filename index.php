<?php
   // Zet foutmeldingen aan om te zien wat er misgaat
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   
   require "config.php";
   
   // Verwijder de login check
   if ($_SESSION['loggedin'] != TRUE) {
      Header("Location: login");
   }

   
   $sel = $con->query("SELECT DISTINCT id, agent, burger FROM livelog ORDER BY id DESC LIMIT 6");
   
   if ($_SERVER['REQUEST_METHOD'] == "POST") {
      if (isset($_POST['anotitie']) AND trim($_POST['anotitie']) != "") {
         $con->query("INSERT INTO anotitie (user_id,text) VALUES ('".$con->real_escape_string($_SESSION['id'])."','".$con->real_escape_string($_POST['anotitie'])."')");
         Header("Location:index");
      }
   }
   
   if (@$_GET['action'] == "deleteanotitie") {
      $con->query("DELETE FROM anotitie WHERE id = '".$_GET['id']."'");
      Header("Location: index.php");
   }
   
   $specialisaties = $con->query("SELECT specialisaties FROM users WHERE id = '".$_SESSION['id']."'");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title><?php echo $site_title; ?></title>
      <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon"/>
      <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
      <link href="css/sb-admin.css" rel="stylesheet">
      <meta name="theme-color" content="<?php echo $browser_color; ?>">
   </head>
   <body class="fixed-nav sticky-footer bg-dark" id="page-top">
      <!-- Navigation-->
      <nav class="navbar navbar-expand-lg navbar-dark bg-red fixed-top" id="mainNav">
         <a class="navbar-brand" href="#"><img id="logo-meos" src="img/logo.png"></a>
         <p id="header-text">MEOS</p>
         <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

              <!-- Default user section-->
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                  <a class="nav-link" href="index">
                  <i class="fa fa-home"></i>
                  <span class="nav-link-text">Homepagina</span>
                  </a>
               </li>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="basisadministratie">
                  <i class="fa fa-fw fa-area-chart"></i>
                  <span class="nav-link-text">Basisadministratie</span>
                  </a>
               </li>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="rdw">
                  <i class="fa fa-fw fa-area-chart"></i>
                  <span class="nav-link-text">Voertuigregistratie</span>
                  </a>
               </li>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="training">
                  <i class="fa fa-fw fa-book"></i>
                  <span class="nav-link-text">Training</span>
                  </a>
               </li>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="aangiftes">
                  <i class="fa fa-fw fa-area-chart"></i>
                  <span class="nav-link-text">Aangifteadministratie</span>
                  </a>
               </li>

               <!-- Admin Section-->
               <?php if ($_SESSION['role'] == "admin") { ?>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="gebruikers">
                  <i class="fa fa-user-circle"></i>
                  <span class="nav-link-text"> Gebruikersadministratie</span>
                  </a>
               </li>
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="jaillog">
                  <i class="fa fa-history"></i>
                  <span class="nav-link-text">Logboeken</span>
                  </a>
               </li>
               <?php } ?>

              <!-- Default user section-->
               <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                  <a class="nav-link" href="exit.php">
                  <i class="fa fa-sign-out"></i>
                  <span class="nav-link-text">Uitloggen</span>
                  </a>
               </li>
            </ul>
         </div>
      </nav>
      <div class="content-wrapper">
      <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
         </li>
      </ol>
      <div class="row">
         <?php
            if ($_SESSION['role'] != "anwb") {
            ?>
         <div class="col-xl-3 col-sm-6 mb-3">
            <a href="basisadministratie" class="fill-div">
               <div class="bg-primary knop">
                  <img id="icon-meos" src="img/user-shape.svg">
                  <p class="text-button">Persoon</p>
               </div>
            </a>
         </div>
         <?php } ?>
         <div class="col-xl-3 col-sm-6 mb-3">
            <a href="rdw" class="fill-div">
               <div class="bg-primary knop">
                  <img id="icon-meos" src="img/sports-car.svg">
                  <p class="text-button">Vervoersmiddel</p>
               </div>
            </a>
         </div>
         <?php
            if ($_SESSION['role'] != "anwb") {
            ?>
         <div class="col-xl-3 col-sm-6 mb-3">
            <a href="aangiftes" class="fill-div">
               <div class="bg-primary knop">
                  <img id="icon-meos" src="img/file.svg">
                  <p class="text-button">Aangifte</p>
               </div>
            </a>
         </div>
         <?php } ?>
         <div class="col-xl-3 col-sm-6 mb-3">
            <a href="beveiligingscentrum" class="fill-div">
               <div class="bg-primary knop">
                  <img id="icon-meos" src="img/White_lock.svg">
                  <p class="text-button">Beveiligingscentrum</p>
               </div>
            </a>
         </div>
      </div>
      <h1>
      <?php 
 
         $tijd = date("G"); //bepaal de tijd in uren 
         
         if($tijd < 6) 
            { 
                  echo "Goedenacht,"; 
            } 
         elseif($tijd < 12) 
            { 
                  echo "Goedemorgen,"; 
            } 
         elseif($tijd < 18)   
            { 
                  echo "Goedemiddag,"; 
            } 
         else 
            { 
                  echo "Goedeavond,";   
            } 
         ?> 
         <?php echo $_SESSION['name']; ?><br>
         In het MEOS systeem van <?php echo $site_name; ?>
      </h1>
      <?php
         if ($_SESSION['role'] != "anwb") {
         ?>
      <h1></br> Algemene Notities</h1>
      <form method="POST">
         <textarea name="anotitie" class="form-control" placeholder="Notitie"></textarea>
         </p>
         <input type="submit" class="btn btn-success" value="Opslaan">
      </form>
      <hr>
      <?php
         $getNotities = $con->query("SELECT n.id as nid, u.id, u.name, n.text, n.date FROM anotitie AS n INNER JOIN users AS u ON u.id=n.user_id ORDER BY n.id DESC LIMIT 10");
         while ($row = $getNotities->fetch_assoc()) {
          ?>
      <b><?php echo htmlspecialchars($row['name']); ?></b> - <em><?php echo $row['date']; ?> <?php if ($_SESSION['id'] == $row['id'] OR $_SESSION['role'] == "admin") { echo "<a href='?action=deleteanotitie&id=".$row['nid']."'>(verwijder)</a>"; } ?></em> <br>
      <?php echo htmlspecialchars($row['text']); ?>
      <hr>
      <?php
         }
         ?>
      <?php } ?>
      <h1>Logboek</h1>
      <br>
      <div class="list-group">
         <?php
            if ($_SESSION['role'] == "admin") {
              $sel = $con->query("SELECT DISTINCT burgerid,agent,burger FROM livelog ORDER BY id DESC LIMIT 6");
              while ($row = $sel->fetch_assoc()) {
            	  if (trim($row['agent']) != "") {
             ?>
         <a href="gegevens.php?id=<?php echo $row['burgerid'] ?>" class="list-group-item">
            <h4 class="list-group-item-heading"><?php echo $row['burger'] ?></h4>
            <p class="list-group-item-text"><?php echo $row['agent'] ?> heeft <?php echo $row['burger']; ?> opgezocht.</p>
         </a>
         <?php ?>
         <?php
            }
            }
            } else {
            echo "Geen toegang tot logboek";
            }
         ?>
<!--          <hr>
          <h1>Signalementen</h1>
         <br>
         <div class="list-group">
            <?php
               if ($_SESSION['role'] != "anwb") {
                 $sel = $con->query("SELECT notitie, gameid FROM informatie WHERE gesignaleerd = true ORDER BY id");
                 while ($row = $sel->fetch_assoc()) {
               	  $user = $ddcon->query("SELECT CONCAT(firstname, ' ', lastname) as name FROM users WHERE identifier = '".$row['gameid']."'");
               
                ?>
            <a href="gegevens.php?id=<?php echo $row['gameid'] ?>" class="list-group-item">
               <h4 class="list-group-item-heading">Signalering van <?php echo $user->fetch_assoc()['name']; ?></h4>
               <p class="list-group-item-text"<?php echo $user->fetch_assoc()['name']; ?> >is voor de politie gesignaleerd: <?php echo $row['notitie']; ?></p>
            </a>
            <?php
               }
               } else {
               echo "Geen toegang tot logboek";
               }
               ?>
         </div> -->
         <footer class="sticky-footer">
            <div class="container">
               <div class="text-center">
                  <small><?php echo $site_footer; ?></small>
               </div>
            </div>
         </footer>
         
         <!-- Scroll to Top Button-->
         <a class="scroll-to-top rounded" href="#page-top">
         <i class="fa fa-angle-up"></i>
         </a>
         <!-- Bootstrap core JavaScript-->
         <script src="vendor/jquery/jquery.min.js"></script>
         <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
         <!-- Core plugin JavaScript-->
         <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
         <!-- Page level plugin JavaScript-->
         <script src="vendor/chart.js/Chart.min.js"></script>
         <script src="vendor/datatables/jquery.dataTables.js"></script>
         <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
         <!-- Custom scripts for all pages-->
         <script src="js/sb-admin.min.js"></script>
         <!-- Custom scripts for this page-->
         <script src="js/sb-admin-datatables.min.js"></script>
         <script src="js/sb-admin-charts.min.js"></script>
      </div>
   </body>
</html>