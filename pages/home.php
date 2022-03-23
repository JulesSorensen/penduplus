<?php 
print_r($_GET);
if(isset($_POST["easy"])){
  $_SESSION["choosenLevel"] = "easy";
  header("refresh:0;url=index.php?p=game");
}elseif (isset($_POST["medium"])) {
  $_SESSION["choosenLevel"] = "medium";
  header("refresh:0;url=index.php?p=game");
}elseif (isset($_POST["hard"])) {
  $_SESSION["choosenLevel"] = "hard";
  header("refresh:0;url=index.php?p=game");
}

?>

<link rel="stylesheet" href="../style/home.css">
<div class="beforecontain">
  <div class="container">
  <div class="card">
      <div class="box">
        <div class="content">
          <h2>02</h2>
          <h3>Card Two</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?</p>
          <div class="container2">
    <div class="center">
      <button  onclick="btneasy()" id="btneasy" name="easy" class="btn">
        <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
        </svg>
        <span>HOVER ME</span>
      </button>
    </div>
  </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="box">
        <div class="content">
          <h2>02</h2>
          <h3>Card Two</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?</p>
          <div class="container2">
    <div class="center">
      <button  id="btnmedium" class="btn">
        <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
        </svg>
        <span>HOVER ME</span>
      </button>
    </div>
  </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="box">
        <div class="content">
          <h2>02</h2>
          <h3>Card Two</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?</p>
          <div class="container2">
    <div class="center">
      <button  id="btnhard" class="btn">
        <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
          <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
        </svg>
        <span>HOVER ME</span>
      </button>
    </div>
  </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

      function btneasy(){
        $.ajax({
        type:"POST",
        url:"index.php?p=home",
        data:{"easy":1},
        success:function(){location.reload();}
      })
      }


</script>