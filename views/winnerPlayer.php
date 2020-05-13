<div class="d-none winner-player" data-player="<?php echo $playerWinner; ?>"></div>

<script>
  $(document).ready(function () {
    setTimeout( function() { 
      const winnerPlayer = $( ".winner-player" ).attr("data-player");
      const updateWinnerPlayer = winnerPlayer === "PlayerOne" ? 0 : 1;

      if ( $( ".player.player__" + updateWinnerPlayer ).length ) {
        $( ".player.player__" + updateWinnerPlayer ).addClass("winner");
        $( ".player.winner h4" ).append(" <i class='fas fa-trophy'></i>");
      }

    }, 3000);
});
</script>