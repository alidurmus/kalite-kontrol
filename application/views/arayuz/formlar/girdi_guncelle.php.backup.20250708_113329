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
                        <td colspan="11">GİRDİ  KONTROL FORMU</td>
                    </tr>
                    <tr>
                        <td colspan="2">Malzeme  Adı:</td>
                        <td colspan="2">
                            <div class="form-group"> 
                                <select id="formInput43" name="malzeme" class="form-control input-sm"> 
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
                                <input type="text" name="kontrol_no" class="form-control input-sm" value="<?php echo $form[0]->kontrol_no; ?>" placeholder="0000"> 
                            </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                        <td colspan="2">Parti Nr:	</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                    <input type="text" class="form-control input-sm"  name="parti_no" value="<?php echo $form[0]->parti_no; ?>" placeholder="Username"> 
                            </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td colspan="2">Tedarikçi Adı:	</td>
                        <td colspan="2">
                            <div class="form-group"> 
                                <select id="formInput43"  name="tedarikci" class="form-control input-sm"> 
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
                                    <input type="text" class="form-control input-sm"  name="irsaliye" value="<?php echo $form[0]->irsaliye; ?>" placeholder="Username"> 
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
                    <?php foreach($json->{'olcum'} as $row => $d) {?>
                        <tr> 
                        <?php foreach($d as $key => $val) {?>
                            <?php // echo $row . ' - ' .$key . ': ' . $val; ?>                                      
                            <?php if($key == "gorsel"){ ?>                                            
                            <td>
                                <div class="input-group"> 
                                    <input type="radio" value="<?php echo $val; ?>" <?php echo ($val == '1') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="Username"> 
                                </div>
                            </td>
                            <td>
                                <div class="input-group"> 
                                    <input type="radio" value="<?php echo $val; ?>" <?php echo ($val == '2') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="Username"> 
                                </div>
                            </td> 
                            <td>
                                <div class="input-group"> 
                                    <input type="radio" value="<?php echo $val; ?>" <?php echo ($val == '3') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="Username"> 
                                </div>
                            </td>
                            <?php }else { ?>
                                <td>
                                        <div class="input-group"> 
                                                <input type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="Username"> 
                                        </div>
                                    </td>
                            <?php } ?>
                        <?php } ?>
                    </tr> 
                            
                    <?php } ?>
                    
                    <tr>                                  
                        <td>Tarih:</td>
                        <td colspan="2">
                            <input type="date" name="tarih" class="form-control input-sm"  value="<?php echo $form[0]->tarih; ?>"/>
                        </td>
                        <td colspan="2">Kullanıcı:</td>
                        <td colspan="2">
                            <div class="input-group"> 
                                <input type="text" name="kullanici" class="form-control input-sm" placeholder="Username"  value="<?php echo $form[0]->kullanici; ?>"> 
                            </div>
                        </td>
                        <td colspan="2">Sonuç:</td>
                        <td colspan="3">
                            <div class="input-group"> 
                                <input type="text" name="sonuc" class="form-control input-sm" placeholder="sonuc"  value="<?php echo $form[0]->sonuc; ?>"> 
                            </div>
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