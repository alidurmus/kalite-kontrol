<!doctype html>
<html lang="tr">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="<?php 
// Load security helper for XSS protection
$this->load->helper('security');
echo base_url("assets"); ?>/assets/js/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="<?php echo base_url("assets"); ?>/assets/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="<?php echo base_url("assets"); ?>/assets/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<title>Anasayfa</title>
</head>
<?php  $css_class="btn btn-success col-lg-12 col-md-12 col-sm-12 col-xs-12 btn-lg btn-block active";  ?>

<body>
  <div class="container">
        <div class="row">
            <div class="col-md-12">
			<a href="<?php echo safe_url($girdi_kontrol); ?>" class="<?php echo safe_output($css_class);?>">Girdi Kontrol Formu</a>
				<a href="<?php echo safe_url($process_kontrol); ?>" class="<?php echo safe_output($css_class);?>">Process Kontrol Formu</a>
				<a href="<?php echo safe_url($final_kontrol); ?>" class="<?php echo safe_output($css_class);?>">Final Kontrol Formu</a>
				<a href="<?php echo safe_url($kullanicilar); ?>" class="<?php echo safe_output($css_class);?>">Kullanıcılar</a>
				<a href="<?php echo safe_url($urunler); ?>" class="<?php echo safe_output($css_class);?>">Ürünler</a>
				<a href="<?php echo safe_url($tedarikciler); ?>" class="<?php echo safe_output($css_class);?>">Tedarikçiler</a>
				<a href="<?php echo safe_url($malzemeler); ?>" class="<?php echo safe_output($css_class);?>">Malzemeler</a>
				<a href="<?php echo safe_url($is_emri_takip); ?>" class="<?php echo safe_output($css_class);?>">İş Emri Takip Çizelgesi</a>
				<a href="<?php echo safe_url($roller); ?>" class="<?php echo safe_output($css_class);?>">Kullanıcı Rolleri</a>
				<a href="<?php echo safe_url($process_takip); ?>" class="<?php echo safe_output($css_class);?>">Process Takip Çizelgesi</a>
			</div>
        </div>
    </div>
</body>

</html>