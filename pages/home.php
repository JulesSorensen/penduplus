<?php
if (isset($_POST["easy"])) { //si la difficulté choisi est facile 
  $_SESSION["choosenLevel"] = "easy";
  header("refresh:0;url=index.php?p=game"); //redirection vers la page de jeu
} elseif (isset($_POST["medium"])) { //si la difficulté choisi est moyenne
  $_SESSION["choosenLevel"] = "medium";
  header("refresh:0;url=index.php?p=game");
} elseif (isset($_POST["hard"])) { //si la difficulté choisi est difficile
  $_SESSION["choosenLevel"] = "hard"; 
  header("refresh:0;url=index.php?p=game");
}

?>

<link rel="stylesheet" href="../style/home.css">
<div class="beforecontain">
  <div class="container">
    <!-- mes cards de niveau -->
    <div class="card">
      <div class="box">
        <div class="content">
          <h2>01</h2>
          <h3>Niveau Facile</h3>
          <img src="../images/easy.png" alt="">
          <p>Mots courants et relativement courts faciles à deviner</p>
          <div class="container2">
            <div class="center">
              <form action="" method="post">
                <!-- mon bouton pour accéder à la page game dfficulté  facile -->
                <button type="submit" name="easy" class="btn">
                  <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
                  </svg>
                  <span>Jouer</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="box">
        <div class="content">
          <h2>02</h2>
          <h3>Niveau Moyen</h3>
          <img src="../images/medium.png" alt="">
          <p>Des mots souvent utilisés mais plus complexes à deviner.</p>
          <div class="container2">
            <div class="center">
              <form action="" method="post">
                <button type="submit" name="medium" class="btn">
                  <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
                  </svg>
                  <span>Jouer</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="box">
        <div class="content">
          <h2>03</h2>
          <h3>Niveau Difficile</h3>
          <img src="../images/hard.png" alt="">
          <p>Mots particulièrement long et complexe à deviner</p>
          <div class="container2">
            <div class="center">
              <form action="" method="post">
                <button type="submit" name="hard" class="btn">
                  <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
                  </svg>
                  <span>Jouer</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

