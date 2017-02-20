<div id="menu">
  <ul>
    <li><a href="<?php echo url_for('/index.php'); ?>">Public Site</a></li>
    <li><a href="<?php echo url_for('/staff/index.php'); ?>">Staff Menu</a></li>
    <li>
    <?php
      if(is_logged_in()){
        echo "<a href=\"".url_for('/staff/logout.php')."\" style=\"color: red;\">Log out</a></li>";
      } else {
        echo "<a href=\"".url_for('/staff/login.php')."\" style=\"color: white;\">Log in</a></li>";
      }
    ?>
  </ul>
</div>
