<?php
  foreach ($initialTiles as $key => $value) {
?>
  <div class="col mb-2">
    <div class="card tile tile__pile--<?php echo $value[0].''.$value[1]; ?>">
      <div class="tile__hide"></div>
      <div class="d-flex justify-content-center align-items-center flex-wrap tile__top tile__num--<?php echo $value[0]; ?>">
        <?php 
          if ($value[0] == 0) {
            echo '<span class="blank"></span>';
          } else {
            for ($x=1; $x <= $value[0]; $x++) { 
              echo '<i class="fas fa-circle"></i>';
            }
          }
        ?>
      </div>
      <div class="d-flex justify-content-center align-items-center flex-wrap tile__bottom tile__num--<?php echo $value[1]; ?>">
        <?php 
          if ($value[1] == 0) {
            echo '<span class="blank"></span>';
          } else {
            for ($x=1; $x <= $value[1]; $x++) { 
              echo '<i class="fas fa-circle"></i>';
            }
          }
        ?>
      </div>
    </div>
  </div>
<?php 
  }
?>