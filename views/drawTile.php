<div class="d-none draw-tile" data-player="<?php echo $valToUpdate['playerUpdate']; ?>" data-val="<?php echo $valToUpdate['tileDraw']; ?>"></div>

<script>
  $(document).ready(function () {
    setTimeout( function() { 
      $( ".draw-tile" ).each(function() {
        const actualDrw = $( this );
        const actualDrwPlayer = actualDrw.attr("data-player");
        const actualDrwTile = actualDrw.attr("data-val");
        const updatePlayer = actualDrwPlayer === "PlayerOne" ? 0 : 1;

        // Removing the tile from the initial pile board
        if ( $( ".tile-container .tile__pile--" + actualDrwTile ).length ) {
          $( ".tile-container .tile__pile--" + actualDrwTile ).addClass("invisible");
        }
          
        // Looking for a empty player tile space to display the new draw tile
        $( ".player.player__" + updatePlayer + " .card").each(function() {
          const actualCard = $( this );

          if ( actualCard.find(".tile__hide").css("opacity") === "1" ) {
            const newCardAtt = "tile__player--" + actualDrwTile;
            const topDots = actualDrwTile.split("")[0];
            const bottomDots = actualDrwTile.split("")[1];
            const newTopClass = "tile__num--" + topDots;
            const newBottomClass = "tile__num--" + bottomDots;
            let topDotsElements = "";
            let bottomDotsElements = "";

            console.log("THIS CAN REMOVED: ",actualCard.attr("data-att"));

            // Removing/Adding the tile classes
            actualCard.attr("class","card tile " + newCardAtt);
            actualCard.attr("data-att",actualDrwTile);

            // Display the draw tile on the player deck
            actualCard.find(".tile__hide").css("opacity","0");
            
            // Removing/Adding the tile (top) drops for the new draw tile on the player deck
            actualCard.find(".tile__top").attr("class","d-flex justify-content-center align-items-center flex-wrap tile__top " + newTopClass);
            actualCard.find(".tile__top").html("");

            if (topDots > 0) {
              for (let index = 1; index <= topDots; index++) {
                topDotsElements = topDotsElements + '<i class="fas fa-circle"></i>';
              }
            }else{
              topDotsElements = '<span class="blank"></span>';
            }

            actualCard.find(".tile__top").html(topDotsElements);

            // Removing/Adding the tile (bottom) drops for the new draw tile on the player deck
            actualCard.find(".tile__bottom").attr("class","d-flex justify-content-center align-items-center flex-wrap tile__bottom " + newBottomClass);
            actualCard.find(".tile__bottom").html("");

            if (bottomDots > 0) {
              for (let index = 1; index <= bottomDots; index++) {
                bottomDotsElements = bottomDotsElements + '<i class="fas fa-circle"></i>';
              }
            }else{
              bottomDotsElements = '<span class="blank"></span>';
            }

            actualCard.find(".tile__bottom").html(bottomDotsElements);

            $( ".draw-tile" ).remove();

            return false;

            // console.log("THIS CAN REMOVED: ",actualCard.attr("class"));
            // console.log("THIS CAN REMOVED 2: ",$( '.player .card[data-att="' + actualDrwTile + '"]' ));

            // $( '.player .card[data-att="' + actualDrwTile + '"]' ).removeClass(".tile__player--" + actualCardVal);
          }
          // }
        });

        $( ".draw-tile" ).remove();
      });
    }, 2500);

  });
</script>