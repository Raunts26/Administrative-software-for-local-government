<?php
$page_title = "Logiraamat";
$page_file = "logiraamat.php";
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
            Logiraamat
            <small>Logimiste vaatamine</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">

            <div class="col-sm-12">

                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Raamat</h3>
                        <div class="box-tools pull-right">
                            <!-- Buttons, labels, and many other things can be placed here! -->
                            <!-- Here is a label for example -->
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <table id="loggingtable" class="table table-striped table-no-border">
                                    <thead id="table_head" style="display: none;">
                                    <tr>
                                        <th>Kasutaja</th>
                                        <th>IP aadress</th>
                                        <th>Staatus</th>
                                        <th>Viimane külastus</th>
                                    </tr>
                                    </thead>
                                    <tbody id="login-data">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>


        </div>


        <!-- Your Page Content Here -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
