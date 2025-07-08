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

	<!-- Latest compiled and minified JavaScript -->
	<script src="<?php echo base_url("assets"); ?>/assets/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<title>Girdi Kontrol Formu</title>
</head>


<body>
  <div class="container">
        <div class="row">
            <div class="col-md-12">
            <?php echo validation_errors(); ?>

            <?php echo form_open('arayuz/girdi'); ?>

            <table class="table table-striped table-sm"> 
                            <tbody>
                                <tr>
                                    <td colspan="10">GİRDİ  KONTROL FORMU</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Malzeme  Adı:</td>
                                    <td colspan="2">
                                        <div class="form-group"> 
                                            <select id="formInput43" name="malzeme" class="form-control"> 
                                                <option>1</option>                                                 
                                                <option>2</option>                                                 
                                                <option>3</option>                                                 
                                            </select>                                             
                                        </div>
                                    </td>
                                    <td></td>
                                    <td colspan="2">Kontrol No</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <input type="text" name="kontrol_no" class="form-control" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Malzeme Kodu:	</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <input type="text" class="form-control"  name="malzeme_kodu" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td></td>
                                    <td colspan="2">Parti Nr:	</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <input type="text" class="form-control"  name="parti_no" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2">Tedarikçi Adı:	</td>
                                    <td colspan="2">
                                        <div class="form-group"> 
                                            <select id="formInput43"  name="tedarikci" class="form-control"> 
                                                <option>1</option>                                                 
                                                <option>2</option>                                                 
                                                <option>3</option>                                                 
                                            </select>                                             
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td colspan="2">İrsaliye No</td>
                                    <td colspan="2">
                                        <div class="input-group"> 
                                            <input type="text" class="form-control"  name="irsaliye" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>                                 
                                <tr>
                                    <td colspan="6"> Metrik Kontroller </td>
                                    <td colspan="5">Görsel Kontroller</td>
                                </tr>                                 
                                <tr>
                                    <td>Ölçüm Adı</td>
                                    <td>Ölçü (mm)</td>
                                    <td>Tolerans	</td>
                                    <td>Alt Limit	</td>
                                    <td>Üst Üst Limit	</td>
                                    <td>Ölçüm</td>
                                    <td>Sonuç</td>
                                    <td>Kontrol Noktası</td>
                                    <td>Uygun</td>
                                    <td>Şartlı Kabul	</td>
                                    <td>Uygun değil</td>
                                </tr>                                 
                              
                                
                                <?php for($i=1; $i<=16; $i++){?>
                                <tr>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="text" class="form-control" name="form[olcum][k<?php echo $i; ?>][adi]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="number" class="form-control" name="form[olcum][k<?php echo $i; ?>][olcu]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="number" class="form-control" name="form[olcum][k<?php echo $i; ?>][tolerans]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="number" class="form-control" name="form[olcum][k<?php echo $i; ?>][alt_limit]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="number" class="form-control" name="form[olcum][k<?php echo $i; ?>][ust_limit]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="number" class="form-control" name="form[olcum][k<?php echo $i; ?>][olcum]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group"> 
                                            <input type="text" class="form-control" name="form[olcum][k<?php echo $i; ?>][sonuc]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td>
                                    <div class="input-group"> 
                                            <input type="text" class="form-control" name="form[olcum][k<?php echo $i; ?>][kontrol_noktasi]" placeholder="Username"> 
                                        </div>
                                    </td>
                                    <td> 
                                        <input class="form-check-input" type="radio" name="form[olcum][k<?php echo $i; ?>][gorsel]" value="1">
                                    </td>
                                    <td> 
                                        <input class="form-check-input" type="radio" name="form[olcum][k<?php echo $i; ?>][gorsel]" value="2">
                                    </td>
                                    <td> 
                                        <input class="form-check-input" type="radio" name="form[olcum][k<?php echo $i; ?>][gorsel]" value="3">
                                    </td>
                                </tr>  
                                <?php } ?>   
                                
                                <tr>
                                    <td>
                                        <td colspan="2">Tarih:</td>
                                        <td colspan="2">
                                            <input type="date" name="tarih" class="form-control"/>
                                        </td>
                                        <td colspan="2">Kullanıcı:</td>
                                        <td colspan="2">
                                            <div class="input-group"> 
                                                <input type="text" name="kullanici" class="form-control" placeholder="Username"> 
                                            </div>
                                        </td>
                                        <td colspan="2">Sonuç:</td>
                                        <td colspan="2">
                                            <div class="input-group"> 
                                                <input type="text" name="sonuc" class="form-control" placeholder="Username"> 
                                            </div>
                                        </td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

           
          

            <?php echo form_submit('mysubmit', 'Submit Post!'); ?>

           <?php echo form_close(); ?>
			
			</div>
        </div>
    </div>
</body>

</html>