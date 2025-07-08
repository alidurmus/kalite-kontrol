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
                <input  type="radio" value="1" <?php echo ($val == '1') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
            </div>
        </td>
        <td>
            <div class="input-group"> 
                <input  type="radio" value="2" <?php echo ($val == '2') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
            </div>
        </td> 
        <td>
            <div class="input-group"> 
                <input  type="radio" value="3" <?php echo ($val == '3') ? 'checked' : ''; ?> name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" placeholder="00"> 
            </div>
        </td>
        <?php }else { ?>
            <td>
                <div class="input-group">               
                    <?php if( $key == "adi" ){ ?>
                        <div > 
                            <input readonly id="adi.<?php echo $row; ?>"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>
                    <?php }elseif( $key == "olcu" ){ ?>
                        <div > 
                            <input readonly id="olcu.<?php echo $row; ?>"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>
                    <?php }elseif( $key == "tolerans" ){ ?>
                        <div > 
                            <input readonly id="tolerans.<?php echo $row; ?>"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>   
                    <?php }elseif( $key == "alt_limit" ){ ?>
                        <div > 
                            <input readonly id="altLimit.<?php echo $row; ?>"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>
                    <?php }elseif( $key == "ust_limit" ){ ?>
                        <div > 
                            <input readonly id="ustLimit.<?php echo $row; ?>"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>
                    <?php }elseif($key == "olcum" ){ ?> 
                        <div > 
                            <input id="olcum.<?php echo $row; ?>" onchange="olcumKontrol('<?php echo $row; ?>')"  type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>    
                    <?php }elseif( $key == "sonuc" ){ ?>
                        <div > 
                            <input readonly id="sonuc.<?php echo $row; ?>" type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                        </div>
                    <?php }else { ?>
                        <input readonly type="text" class="form-control input-sm" value="<?php echo $val; ?>" name="form[olcum][<?php echo $row; ?>][<?php echo $key; ?>]" > 
                    <?php } ?>    
                </div>
                </td>
        <?php } ?>
    <?php } ?>
</tr>         
<?php } ?>
<tr>
    <td colspan="2">Açıklama:</td>
    <td colspan="9">
        <div class="form-group">
            <textarea class="form-control" name="aciklama" rows="2" id="comment"></textarea>
    </td>
</tr>
<tr>                                  
    <td>Tarih:</td>
    <td colspan="2">
        <?php echo date("Y-m-d H:i:s"); ?>
    </td>
    <td colspan="1">Sonuç:</td>
    <td colspan="2">
        <div class="form-group">
            <select name="sonuc" class="form-control">  
                <?php foreach($sonuc_secim as $sonuc_secim_litem) { ?>                                       
                    <option value="<?php echo $sonuc_secim_litem->id; ?>"><?php echo $sonuc_secim_litem->adi; ?></option>
                <?php } ?>
            </select>      
        </div><!-- .form-group -->        
    </td>
    <td colspan="3"></td>
    <td colspan="3">                           
    </td>
</tr>

<script>
//TODO : olçüm dğerleri 888 üç basamak ayıramıyor
// 88 ile 888 aynı gibi görüyor
function olcumKontrol(index) {
    var kalite = new Object();
    kalite.index = Number(index);
    kalite.olcum = Number(document.getElementById("olcum." + index).value);
    kalite.altLimit = Number(document.getElementById("altLimit." + index).value);
    kalite.ustLimit = Number(document.getElementById("ustLimit." + index).value);
    kalite.sonuc = "";    
    
    inputIndex=document.getElementById("sonuc." + index);

    if (kalite.olcum < kalite.altLimit) {    
        inputIndex.value="Kaldı";
        inputIndex.style.backgroundColor = "red";
        inputIndex.style.color = "white";
    }
    else if(kalite.olcum > kalite.ustLimit) {
        inputIndex.value="Kaldı";
        inputIndex.style.backgroundColor = "red";
        inputIndex.style.color = "white";
    }
    else{
        inputIndex.value="Geçti";
        inputIndex.style.backgroundColor = "green";
        inputIndex.style.color = "white";
    }  
}

</script>