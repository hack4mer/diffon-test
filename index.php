<!doctype html>
<html lang="en">
<?php
//Template credit: http://www.creative-tim.com
include 'vendor/autoload.php';
include 'Helper.php';

use Hack4mer\Diffon\Diffon;

//Well aware of the security hole here
//Should not be pushed to production server
$source = empty($_GET['src']) ?  "v1":$_GET['src']; 
$destination = empty($_GET['dest']) ?  "v2":$_GET['dest'];

$diffon = new Diffon();
$diffon->setSource($source)->setDestination($destination)->setRecursiveMode(true);

$diff = $diffon->diff();

$helper = new Helper();
$insight = $helper->extract_insight($diff);

$insight_count = [];

foreach ($insight as $key => $value) {
  $insight_count[$key] = count($value);
}
arsort($insight_count);

if(count($insight) > 0){
  $max_diff_in    = array_keys($insight_count)[0]; 
  $max_diff_count = $insight_count[$max_diff_in];
}else{
  $max_diff_count = 0;  
}


$current_dir_contents = array_merge($diff['in_both'],$diff['only_in_source'],$diff['only_in_destination']);
?>
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Diffon
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">
        <a href="<?=$_SERVER['PHP_SELF']?>" class="simple-text logo-normal">
          Diffon
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=$_SERVER['PHP_SELF']?>">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <!-- your sidebar here -->
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
         
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">

          <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Version difference overview</h4>
                  <p class="card-category">Overview of the differences in the two website versions</p>
                </div>
                <div class="card-body table-responsive">
                  <table class="table table-hover">
                    <thead class="text-warning">
                      <th>Files only in v1</th>
                      <th>Files only in v2</th>
                      <th>Files in both, but different data</th>
                    </tr></thead>
                    <tbody>
                      <?php 
                      for ($i=0; $i < $max_diff_count; $i++) { 
                        echo '<tr>';

                        echo '<td>'.@$insight['only_in_source'][$i].'</td>';
                        echo '<td>'.@$insight['only_in_destination'][$i].'</td>';
                        echo '<td>'.@$insight['not_same'][$i].'</td>';

                        echo '</tr>';
                      }                      
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>


              <div class="card" style="margin-top: 80px;">
                <div class="card-header">
                  <h4 class="card-title">Browse difference</h4>
                  <p class="card-category">Select a directory to see its differences</p>
                </div>
                <div class="card-body table-responsive">
                  <table class="table table-hover">
                    <thead class="text-warning">
                      <th>v2</th>
                      <th>v2</th>
                    </tr></thead>
                    <tbody>
                      <?php 
                      for ($i=0; $i < count($current_dir_contents); $i++) { 

                        //entity  = file/dir
                        $entity = $current_dir_contents[$i];

                        $highlight_color = "#fff";

                        if(in_array($entity, $diff['only_in_source'])){
                          $v1 = $entity;
                          $v2 = '';

                          $highlight_color = "red";
                        }else if(in_array($entity, $diff['only_in_destination'])){
                          $v2 = $entity;
                          $v1 = '';

                          $highlight_color = "red";
                        }else{
                          $v1 = $v2 = $entity;

                          if(is_dir($source.'/'.$v1) && is_dir($destination.'/'.$v2)){

                            $browse_url = '?src='.urlencode($source.'/'.$v1).'&dest='.urlencode($destination.'/'.$v2); 
                            $v1 = $v2   = "<a href=\"$browse_url\">$v1</a>";

                            $highlight_color = "#ccffff";
                          }
                        }

                        echo '<tr style="background-color:'.$highlight_color.'">';

                        echo '<td>'.$v1.'</td>';
                        echo '<td>'.$v2.'</td>';

                        echo '</tr>';
                      }                      
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://hack4mer.me" target="_blank">
                  @hack4mer
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            Template credit:  
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>.
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>
</body>

</html>