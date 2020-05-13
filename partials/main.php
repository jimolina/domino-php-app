<?php
  require_once("./classes/Domino.php");

  $domino = new Domino();
  // $initialTiles = $domino->createTiles();

  // print_r($initialTiles);
?>
<div class="container my-5">
  <div class="row">
    <div class="col-12 col-md-4">
      <h3>Initital Pile Tiles</h3>
      <div class="alert alert-primary mt-4" role="alert">
        <small>You can move the mouse over each tile in order to verify the random shuffle everytime the page reload.</small>
        <i class="far fa-hand-point-down"></i>
      </div>
      <div class="tile-container py-4 row row-cols-6 no-gutters">
        <?php $domino->createTiles(); ?>
      </div>
    </div>
    <div class="col-12 col-md-8 text-right">
      <h3>Players</h3>
      <div class="tile-players pt-4">
        <?php $domino->shuffleTiles(); ?>
      </div>
      <div class="play mt-4">
        
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">

      <div class="mt-4">
        <div class="alert-primary p-2">
          <h3 class="mb-0"><i class="fas fa-archive"></i> <strong>LOG</strong></h3>
        </div>
        <div class="console border text-left p-4">
          <?php 
              try {
                $domino->playGame(); 
                throw new Exception();
              }
              catch (Exception $e) {
                  echo "Something went wrong, please reload the page! n";
              }
          ?>
          <pre><?php var_dump($_SESSION["dominoLog"]); ?>
          </pre>
        </div>
      </div>

    </div>
  </div>
</div>