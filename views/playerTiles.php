<?php
  foreach ($playersTiles as $key => $player) {
?>
    <div class="player player__<?php echo $key ?>">
      <h4><i class="fas fa-gamepad"></i> Player <?php echo ($key + 1); ?></h4>
      <div class="row row-cols-7">
        <?php
          foreach ($player as $key => $value) {
        ?>
            <div class="col mb-4">
              <div class="card tile tile__player--<?php echo $value[0].$value[1]; ?>" data-att="<?php echo $value[0].$value[1]; ?>">
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
      </div>
    </div>
<?php 
  }
?>
<script>
  $(document).ready(function () {
    let time = 300;

    $( ".tile-players .card.tile" ).each(function( index ) {
      const actualTile = $( this );
      const actualVal = actualTile.attr("data-att");
      const pileTile = $( ".tile.tile__pile--" +  actualVal);

        setTimeout( function() { 
          pileTile.addClass("invisible");
          actualTile.children(".tile__hide").delay(200).animate({ 
              opacity: "0" 
          });
          actualTile.children(".tile__hide").addClass("show");
        }, time)

      time += 100;
    });
  });
</script>