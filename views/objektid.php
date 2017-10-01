<?php	$page_title = "Objektid";	$page_file = "objektid.php";?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <section class="content-header">
   <h1>
     Hoonepass
     <small>Hoonete ülevaade</small>
   </h1>
   <ol class="breadcrumb">
     <li><button class="btn btn-box-tool" data-toggle="modal" data-target="#add_object"><span class="label label-success font-ok">Lisa</span></button></li>
   </ol>
 </section>
 <!-- Main content -->
 <section class="content">
 <div class="row">

   <?php if(isset($_SESSION['error_msg'])): ?>
     <div class="col-sm-12">
       <div class="alert alert-warning alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <h4><i class="icon fa fa-warning"></i> Tähelepanu!</h4>
         <?=$_SESSION['error_msg'];?>
         <?php
         if(count($error) > 0) {
           for($i = 0; $i < count($error); $i++) {
             if($i == count($error) - 1) {
               echo $error[$i];
             } else {
               echo $error[$i] . ", ";
             }
           }
         }
         ?>
       </div>
     </div>
   <?php $_SESSION['msg_seen'] = true; endif; ?>

   <?php if(isset($_SESSION['success_msg'])): ?>
     <div class="col-sm-12">
       <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <h4><i class="icon fa fa-check"></i> Salvestatud!</h4>
         <?=$_SESSION['success_msg'];?>
       </div>
     </div>
   <?php $_SESSION['msg_seen'] = true; endif; ?>


      <div class="col-sm-6">

        <div class="form-group">
    	  <label for="select-1">Vali objekt</label>
    	   <?=$Objects->fillSelect();?>
    	  </div>

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Ehitise üldandmed</h3>
            <div class="box-tools pull-right">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- Here is a label for example -->
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <p>
                  <strong>Ehitisregistri kood:</strong>
                  <span id="code_answer"></span>
                </p>
                <p>
                  <strong>Esmase kasutuselevõtu aasta:</strong>
                  <span id="year_answer"></span>
                </p>
                <p>
                  <strong>Peamine kasutamise otstarve:</strong>
                  <span id="usedfor_answer"></span>
                </p>
                <p>
                  <strong>Ehitise koha-aadress:</strong>
                  <span id="address_answer"></span>
                </p>
                <p>
                  <strong>Kontaktisik:</strong>
                  <span id="contact_answer"></span>
                </p>
                <p>
                  <strong>Email:</strong>
                  <span id="email_answer"></span>
                </p>
                <p>
                  <strong>Number:</strong>
                  <span id="number_answer"></span>
                </p>

                <button id="edit_btn" type="button" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#edit_object" disabled>
                  <i class="fa fa-pencil"></i>
                </button>
              </div>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->


      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab">Üldandmed</a></li>
          <li><a href="#tab_2" data-toggle="tab">Tehnilised</a></li>
          <li><a href="#tab_3" data-toggle="tab">Konstruktsioonid</a></li>
          <li><a href="#tab_4" data-toggle="tab">Küttesüsteemid</a></li>

          <li class="pull-right"><button id="meta_btn_add" class="btn btn-box-tool" data-toggle="modal" data-target="#add_objectmeta" style="padding: 10px;" disabled><i class="fa fa-pencil"></i></button></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1"></div>
          <div class="tab-pane" id="tab_2"></div>
          <div class="tab-pane" id="tab_3"></div>
          <div class="tab-pane" id="tab_4"></div>

        </div><!-- /.tab-content -->
      </div><!-- nav-tabs-custom -->



	  </div>






	  <div class="col-sm-6">
      <div class="form-group">
        <label for="select-1">Vali plaan (klikkides plaanil, näed suuremalt)</label>
        <select id="floor_plan" class="form-control">
      	  <option selected> - Vali - </option>

        </select>
      </div>

      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Plaan</h3>
          <div class="box-tools pull-right">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div id="plan-me" class="col-sm-12">
              <a id="floor-link" href="" target="_blank">
          	     <img id="floor-img" src="<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg" class="img-responsive" onerror="this.src='<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg'">
              </a>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
          <div class="row">
            <div class="col-xs-1 col-sm-1 col-lg-1">

              <button id="delete_picture" class="btn btn-default" disabled><i class="fa fa-trash"></i></button>

            </div>
            <div class="col-xs-11 col-sm-11 col-lg-11">

              <form method="post" enctype="multipart/form-data">
                <input id="plan_add_image" type="file" name="plan-pdf" accept=".pdf" style="display: none;">
                <input id="plan_add_png" type="file" name="plan-img" accept=".jpeg,.jpg,.png." style="display: none;">
                <input id="plan_upload_id" type="hidden" name="plan_upload_id">
                <div class="input-group">
                  <input id="plan_add_name" type="text" name="plan_name" class="form-control" placeholder="Plaani nimi">
                  <div class="input-group-btn">

                    <button id="click_image" class="btn btn-default" type="button">
                      <i class="fa fa-file-image-o"></i>
                    </button>

                    <button id="click_pdf" class="btn btn-default" type="button">
                      <i class="fa fa-file-pdf-o"></i>
                    </button>

                    <button id="plan_add_upload" class="btn btn-default" type="submit" name="upload_new_plan" disabled>
                      <i class="fa fa-upload"></i>
                    </button>


                  </div>
                </div>
              </form>
              <span id="gonna_upload_pdf"></span><br>
              <span id="gonna_upload_img"></span>


            </div>
          </div>


        </div>
      </div><!-- /.box -->

      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Dokumendid -  juhendid, manuaalid</h3>
          <div class="box-tools pull-right">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12">
              <ul id="doc-list" class="doc-list">
                <!-- js saadab siia andmed -->
              </ul>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
          <!--
          # Dokumentide üleslaadimine
          # Kui ühele lehele tuleb mitu üleslaadimist siis tuleb ID'si muuta, ID ei tohi korduda!
          # Samuti tuleb js'i juurde kirjutada ka teisele IDle vastavad tegurid
          # Kõige lihtsam on ID lõppu lisada number, seda ka js poole pealt
          -->
          <form id="doc_form" name="form" action="../inc/upload.php" method="POST" enctype="multipart/form-data" >

            <div class="input-group">
              <input type="text" id="attach-newname" name="attach-newname" class="form-control" placeholder="Dokumendi nimi" required disabled>
              <div class="input-group-btn">
                <button id="attach-btn" class="btn btn-default" type="button" disabled>
                  <span class="glyphicon glyphicon-paperclip"></span>
                </button>
                <button id="add-attach" type="submit" class="btn btn-default" disabled>
                  <span class="glyphicon glyphicon-cloud-upload"></span>
                </button>
              </div>
            </div>

            <span id="attach-names"></span>
            <input id="attach-new" type="file" name="my_files[]" multiple>
            <input id="attach-id" type="hidden" name="attach-id">
            <input id="attach-what" type="hidden" name="attach-what">
            <!-- Values määrata dokumendi tüüp (1 - Hooldus, 2 - Tehniline, 3 - Muu) -->
            <input id="attach-type" type="hidden" name="attach-type" value="1">


          </form>
          <!--<iframe id='my_iframe' name='my_iframe' src="" style="display: none;"></iframe> <!-- Sellel ära muuda midagi -->
          <!-- Siin lõppeb üleslaadimine -->

        </div>
      </div><!-- /.box -->

	  </div>

    <!-- Objektide lisamine -->

    <div class="modal fade" id="add_object" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Objekti lisamine</h4>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="name">Objekti nimi:</label>
              <input class="form-control" type="text" id="name_new" required>
            </div>

            <div class="form-group">
              <label for="name">Ehitusregistri kood:</label>
              <input class="form-control" type="text" id="code_new" required>
            </div>

            <div class="form-group">
              <label for="add_org">Esmase kasutuselevõtu aasta: </label>
              <input class="form-control" id="year_new" required>
                </div>

            <div class="form-group">
              <label for="phone">Peamine kasutamise otstarve: </label>
              <input class="form-control" type="text" id="usedfor_new" required>
            </div>

            <div class="form-group">
              <label for="info">Ehitise koha-aadress: </label>
              <input class="form-control" type="text" id="address_new" required>
            </div>

            <div class="form-group">
              <label for="info">Kontaktisik: </label>
              <input class="form-control" type="text" id="contact_new" required>
            </div>

            <div class="form-group">
              <label for="info">Email: </label>
              <input class="form-control" type="text" id="email_new" required>
            </div>

            <div class="form-group">
              <label for="info">Number: </label>
              <input class="form-control" type="text" id="number_new" required>
            </div>


          </div>
          <div class="modal-footer">
            <button id="close_obj" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            <button id="save_obj" type="button" class="btn btn-success">Salvesta</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Objektide muutmine -->

    <div class="modal fade" id="edit_object" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Objektide muutmine</h4>
          </div>
          <div class="modal-body">

            <input class="form-control" type="hidden" id="object_edit_id">

            <div class="form-group">
              <label for="name">Objekti nimi:</label>
              <input class="form-control" type="text" id="name_edit" required>
            </div>

            <div class="form-group">
              <label for="name">Ehitusregistri kood:</label>
              <input class="form-control" type="text" id="code_edit" required>
            </div>

            <div class="form-group">
              <label for="add_org">Esmase kasutuselevõtu aasta: </label>
              <input class="form-control" id="year_edit" required>
                </div>

            <div class="form-group">
              <label for="phone">Peamine kasutamise otstarve: </label>
              <input class="form-control" type="text" id="usedfor_edit" required>
            </div>

            <div class="form-group">
              <label for="info">Ehitise koha-aadress: </label>
              <input class="form-control" type="text" id="address_edit" required>
            </div>

            <div class="form-group">
              <label for="info">Kontaktisik: </label>
              <input class="form-control" type="text" id="contact_edit" required>
            </div>

            <div class="form-group">
              <label for="info">Email: </label>
              <input class="form-control" type="text" id="email_edit" required>
            </div>

            <div class="form-group">
              <label for="info">Number: </label>
              <input class="form-control" type="text" id="number_edit" required>
            </div>



          </div>
          <div class="modal-footer">
            <button id="delete_obj" type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-trash"></i></button>
            <button id="close_edit_obj" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            <button id="save_new_obj" type="button" class="btn btn-success">Salvesta</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Objektide metaandmete lisamine -->

    <div class="modal fade" id="add_objectmeta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header rmv-pdg-bottom">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <!-- Custom Tabs -->
            <div class="nav-tabs-custom modal-nav">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_5" data-toggle="tab">Üldandmed</a></li>
                <li><a href="#tab_6" data-toggle="tab">Tehnilised</a></li>
                <li><a href="#tab_7" data-toggle="tab">Konstruktsioonid</a></li>
                <li><a href="#tab_8" data-toggle="tab">Küttesüsteemid</a></li>

              </ul>
            </div><!-- nav-tabs-custom -->

          </div>
          <div class="modal-body">

            <div class="row">

              <input class="form-control" type="hidden" id="object_meta_id">

              <div class="tab-content">
                <div class="tab-pane active" id="tab_5">

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Ehitisealune pind (m2)</label>
                      <input class="form-control meta_data" data-name="Ehitisealune pind (m2)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Maapealse osa alune pind (m2)</label>
                      <input class="form-control meta_data" data-name="Maapealse osa alune pind (m2)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Maapealsete korruste arv</label>
                      <input class="form-control meta_data" data-name="Maapealsete korruste arv" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Maa-aluste korruste arv</label>
                      <input class="form-control meta_data" data-name="Maa-aluste korruste arv" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Absoluutne kõrgus (m)</label>
                      <input class="form-control meta_data" data-name="Absoluutne kõrgus (m)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Kõrgus (m)</label>
                      <input class="form-control meta_data" data-name="Kõrgus (m)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Majandamiskulud (m2)</label>
                      <input class="form-control meta_data" data-name="Majandamiskulud (m2)" type="text" data-type="1">
                    </div>

                  </div>

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Pikkus (m)</label>
                      <input class="form-control meta_data" data-name="Pikkus (m)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Laius (m)</label>
                      <input class="form-control meta_data" data-name="Laius (m)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Sügavus (m)</label>
                      <input class="form-control meta_data" data-name="Sügavus (m)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Suletud netopind (m2)</label>
                      <input class="form-control meta_data" data-name="Suletud netopind (m2)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Köetav pind (m2)</label>
                      <input class="form-control meta_data" data-name="Köetav pind (m2)" type="text" data-type="1">
                    </div>

                    <div class="form-group">
                      <label for="name">Maht (m3)</label>
                      <input class="form-control meta_data" data-name="Maht (m3)" type="text" data-type="1">
                    </div>

                  </div>


                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_6">

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Elektrisüsteemi liik</label>
                      <input class="form-control meta_data" data-name="Elektrisüsteemi liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Veevarustuse liik</label>
                      <input class="form-control meta_data" data-name="Veevarustuse liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Kanalisatsiooni liik</label>
                      <input class="form-control meta_data" data-name="Kanalisatsiooni liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Soojusvarustuse liik</label>
                      <input class="form-control meta_data" data-name="Soojusvarustuse liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Soojusallikas</label>
                      <input class="form-control meta_data" data-name="Soojusallikas" type="text" data-type="2">
                    </div>

                  </div>

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Energiaallikas</label>
                      <input class="form-control meta_data" data-name="Energiaallikas" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Ventilatsiooni liik</label>
                      <input class="form-control meta_data" data-name="Ventilatsiooni liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Jahutussüsteemi liik</label>
                      <input class="form-control meta_data" data-name="Jahutussüsteemi liik" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Võrgu- või mahutigaasi olemasolu</label>
                      <input class="form-control meta_data" data-name="Võrgu- või mahutigaasi olemasolu" type="text" data-type="2">
                    </div>

                    <div class="form-group">
                      <label for="name">Täiendavad andmed</label>
                      <input class="form-control meta_data" data-name="Täiendavad andmed" type="text" data-type="2">
                    </div>

                  </div>


                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_7">

                  <div class="col-sm-6">

				  <div class="form-group">
                      <label for="name">Vundamendi liik</label>
                      <input class="form-control meta_data" data-name="Vundamendi liik" type="text" data-type="3">
                    </div>

                    <div class="form-group">
                      <label for="name">Kande- ja jäigastavate konstruktsioonide materjal</label>
                      <input class="form-control meta_data" data-name="Kande- ja jäigastavate konstruktsioonide materjal" type="text" data-type="3">
                    </div>



                    <div class="form-group">
                      <label for="name">Välisseina välisviimistluse materjal</label>
                      <input class="form-control meta_data" data-name="Välisseina välisviimistluse materjal" type="text" data-type="3">
                    </div>

                    <div class="form-group">
                      <label for="name">Välisseina liik</label>
                      <input class="form-control meta_data" data-name="Välisseina liik" type="text" data-type="3">
                    </div>

                  </div>

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Katuste ja katuselagede kandva osa materjal</label>
                      <input class="form-control meta_data" data-name="Katuste ja katuselagede kandva osa materjal" type="text" data-type="3">
                    </div>

                    <div class="form-group">
                      <label for="name">Vahelagede kandva osa materjal</label>
                      <input class="form-control meta_data" data-name="Vahelagede kandva osa materjal" type="text" data-type="3">
                    </div>

                    <div class="form-group">
                      <label for="name">Katusekatte materjal</label>
                      <input class="form-control meta_data" data-name="Katusekatte materjal" type="text" data-type="3">
                    </div>

                  </div>


                </div>
				 <div class="tab-pane" id="tab_8">
				 <div class="col-sm-6">

                    <div class="form-group">
                      <label for="name">Kütteseadme tüüp</label>
                      <input class="form-control meta_data" data-name="Kütteseadme tüüp" type="text" data-type="4">
                    </div>

                    <div class="form-group">
                      <label for="name">Kütteliik</label>
                      <input class="form-control meta_data" data-name="Kütteliik" type="text" data-type="4">
                    </div>

                    <div class="form-group">
                      <label for="name">Seadmete arv</label>
                      <input class="form-control meta_data" data-name="Seadmete arv" type="text" data-type="4">
                    </div>

					<div class="form-group">
                      <label for="name">Võimsus</label>
                      <input class="form-control meta_data" data-name="Võimsus" type="text" data-type="4">
                    </div>

                    <div class="form-group">
                      <label for="name">Ekspluatatsiooni aasta</label>
                      <input class="form-control meta_data" data-name="Ekspluatatsiooni aasta" type="text" data-type="4">
                    </div>
					</div>
					<div class="col-sm-6">

					<div class="form-group">
                      <label for="name">Hoolduse maksumus/kuus</label>
                      <input class="form-control meta_data" data-name="Hoolduse maksumus/kuus" type="text" data-type="4">
                    </div>

					<div class="form-group">
                      <label for="name">Hooldaja</label>
                      <input class="form-control meta_data" data-name="Hooldaja" type="text" data-type="4">
                    </div>
					<div class="form-group">
                      <label for="name">Märkused</label>
                      <input class="form-control meta_data" data-name="Märkused" type="text" data-type="4">
                    </div>

                  </div>

				<!-- /.tab-pane -->

            </div>


            </div>
          </div>
          <div class="modal-footer">
            <button id="close_addmeta" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            <button id="save_new_meta" type="button" class="btn btn-success">Salvesta</button>
          </div>
        </div>
      </div>
    </div>



  	   </section><!-- /.content -->
    </div><!-- /.content-wrapper -->




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datepicker/bootstrap-datepicker.js"></script>

    <script>


    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    });
    </script>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>

  <script>
  var objectid = <?=$_SESSION['upload_id'];?>;

  document.querySelector("#object_select").value = objectid;


  </script>

  <?php
    unset($_SESSION['success_msg']);
    unset($_SESSION['error_msg']);
  ?>

  <?php
  if(isset($_SESSION['upload_id'])) {
    unset($_SESSION['upload_id']);
  }

  ?>
