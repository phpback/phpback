<!--  PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.  -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>favicon.ico" sizes="16x16">

    <!-- Loading Bootstrap -->
    <link href="<?= base_url(); ?>public/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?= base_url(); ?>public/bootstrap/css/prettify.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="<?= base_url(); ?>public/css/flat-ui.css" rel="stylesheet">
    <!-- <link href="<?= base_url(); ?>public/css/demo.css" rel="stylesheet"> -->

    <!-- Loading custom styles-->
    <link href="<?php echo base_url(); ?>public/css/all.css" rel="stylesheet">

  <script type="text/javascript">
  function showtable(tableid, tablelink){
      document.getElementById('activitytable').style.display = 'none';
      document.getElementById('ideastable').style.display = 'none';
      document.getElementById('commentstable').style.display = 'none';
      document.getElementById(tableid).style.display = '';
      document.getElementById("table1").className = "";
      document.getElementById("table2").className = "";
      document.getElementById("table3").className = "";
      document.getElementById(tablelink).className = "active";
  }
  function showtable4(tableid, tablelink){
      document.getElementById('resetvotestable').style.display = 'none';
      document.getElementById('changepasswordtable').style.display = 'none';
      document.getElementById(tableid).style.display = '';
      document.getElementById("table4").className = "";
      document.getElementById("table5").className = "";
      document.getElementById(tablelink).className = "active";
  }
  function popup_sure(text, url) {
        if (confirm(text) == true) {
           window.location = url;
        }
  }
  </script>
</head>
<body>
  <div class="row header">
    <div class="pull-left header--title-container">
      <h4 id="header--title"><?= $title; ?></h4>
    </div>
    <?php if(@isset($_SESSION['phpback_userid'])): ?>
    <div class="pull-right" style="padding-top:15px;padding-right:40px;">
      <small><span class="logged-as-label"><?= $lang['label_logged_as']; ?></span>
          <span style='color:#999;margin-left:5px;'>
            <a href="<?php echo base_url() . 'home/profile/' . $_SESSION['phpback_userid'].'/'.Display::slugify($_SESSION['phpback_username']); ?>"><?php echo $_SESSION['phpback_username']; ?></a>
          </span>
      <a href="<?php echo base_url() . 'action/logout'; ?>"><button type="button" class="btn btn-danger btn-xs" style="margin-left:10px;"><?php echo $lang['label_log_out']; ?></button></a></small>
    </div>
    <?php else : ?>
    <div class="pull-right" style="padding-top:12px;padding-right:40px;">
      <a href="<?php echo base_url() . 'home/login'; ?>"><button type="button" class="btn btn-success btn-sm btn-block" style="width:250px"><?php echo $lang['label_log_in']; ?></button></a>
    </div>
    <?php endif; ?>

  </div>
  
  <div class="container">
  <div class="row">
