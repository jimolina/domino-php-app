<?php
  foreach ($valToUpdate as $key => $value) {
?>
  <div class="d-none update-tiles" data-name="<?php echo $key; ?>" data-val="<?php echo $value; ?>"></div>
<?php 
  }
?>

<script>
  $(document).ready(function () {
    setTimeout( function() { 
      $( ".update-tiles" ).each(function( index ) {
        const actualUp = $( this );
        const actualUpName = actualUp.attr("data-name");
        const actualUpValue = actualUp.attr("data-val");
        
        if ($(".tile-players .tile__player--" + actualUpValue).length) {
          // console.log("VIENE", $(".tile-players .tile__player--" + actualUpValue + " .tile__hide").css("opacity"));

            if ($(".tile-players .tile__player--" + actualUpValue + " .tile__hide").css("opacity") == 0) {
              // console.log("VIENE A: ", actualUpName);
              // console.log("VIENE B: ", actualUpValue);
              $(".tile-players .tile__player--" + actualUpValue + " .tile__hide").css("opacity","1");
            }

        }

        // $( ".update-tiles" ).remove();
      });
    }, 2500);
  });
</script>