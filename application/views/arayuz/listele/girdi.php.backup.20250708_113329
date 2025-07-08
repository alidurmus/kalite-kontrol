<!doctype html>
<html lang="tr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="<?php echo base_url("assets"); ?>/assets/js/jquery.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/jquery.dataTables.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo base_url("assets"); ?>/assets/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="<?php echo base_url("assets"); ?>/assets/js/jquery.dataTables.min.js"></script>
    <title>Girdi Kontrol Formları</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-right">
                <hr>
                <a href="http://localhost/cms/panel/anasayfa/malzeme" class="btn btn-success">Yeni Ekle</a>
                <hr>
            </div>
            <div class="col-md-12">
                <table id="dataTable" class="table table-hover table-striped table-bordered content-container">
                    <thead>
                        <th>Malzeme</th>
                        <th>Tedarikçi</th>
                        <th>Kontrol No</th>
                        <th>Parti_no</th>
                        <th>İrsaliye</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </thead>
                    <tbody class="sortable" data-url="<?php echo base_url("girdikontrol/rankSetter"); ?>">
                    <?php foreach($formlar as $form) { ?>
                        <tr>
                            <td><?php echo $form->malzeme_adi; ?></td>
                            <td><?php echo $form->tedarikci_adi; ?></td>
                            <td><?php echo $form->kontrol_no; ?></td>
                            <td><?php echo $form->parti_no; ?></td>
                            <td><?php echo $form->irsaliye; ?></td>
                            <td><?php echo $form->tarih; ?></td>
                            <td>
                                <a href="<?php echo base_url("index.php/arayuz/girdi_guncelle"); ?>?form_id=<?php echo $form->id;?>" class="btn btn-info">Düzenle</a>
                                <a href="#" class="btn btn-danger">Sil</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
</body>

</html>