<?php
	$page_title = "Objektid";
	$page_file = "objektid.php";
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            VÄLIKORISTUSTÖÖDE PÄEVIK

            <small>VÄLIKORISTUSTÖÖDE PÄEVIK</small> NB! SEE võiks käia iga asutuse külge.
          </h1>
        </section>

		  <!-- Main content -->
        <section class="content">

          <div class="row">

      <div class="col-sm-6">
        <div class="form-group">
          <label for="select-1">Vali asutus</label>
          <select class="form-control">
            <option selected> Võsukese lasteaed </option>
            <option>Võsukese lasteaed</option>
          </select>
        </div>



        <label for="stuff">VÄLIKORISTUSTÖÖDE PÄEVIK	</label>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="col-sm-6">
              <b>Tööde teostamise aeg (kuupäev ja töödega alustamise ning lõpetamise kellaaeg)	</b>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>
        	  <p><input type="text" /></p>



            </div>
			 <div class="panel-body">
            <div class="col-sm-6">
              <b>Tööde teostaja nimi</b>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>
               <p><input type="text" /></p>


            </div>

            <div class="col-sm-12">
              <button id="add" type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Salvestan</button>
					<label>
                      <input type="checkbox"> Kinnitan andmete õigsust
                      </label><br>
					  <label>KUUPÄEV</label>

            </div>
          </div>
        </div>

      </div>










<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="js/app.js"></script>
<script src="js/playground.js"></script>
<script src="js/properties.js"></script>

<script>
  window.onload = function() {
    var app = new App();

  };
</script>


</body>
</html>
