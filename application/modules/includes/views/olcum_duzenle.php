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
    <td>Uygun Değil</td>
</tr> 
<?php 
// Load security helper for XSS protection
$this->load->helper('security');

// Migration sonrası JSON verisi null olabilir - kontrol ekle
if ($json && isset($json->olcum) && is_array($json->olcum)) {
    foreach($json->{'olcum'} as $row => $d) {?>
    <tr> 
    <?php foreach($d as $key => $val) {?>
        <?php // echo $row . ' - ' .$key . ': ' . $val; ?>                                      
        <?php if($key == "gorsel"){ ?>                                            
        <td>
            <div class="input-group"> 
                <input  type="radio" value="1" <?php echo ($val == '1') ? 'checked' : ''; ?> name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" placeholder="00"> 
            </div>
        </td>
        <td>
            <div class="input-group"> 
                <input  type="radio" value="2" <?php echo ($val == '2') ? 'checked' : ''; ?> name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" placeholder="00"> 
            </div>
        </td> 
        <td>
            <div class="input-group"> 
                <input  type="radio" value="3" <?php echo ($val == '3') ? 'checked' : ''; ?> name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" placeholder="00"> 
            </div>
        </td>
        <?php }else { ?>
        <td>
            <div class="input-group">               
                <?php if( $key == "adi" ){ ?>
                    <div > 
                        <input readonly id="adi.<?php echo safe_output($row); ?>"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>
                <?php }elseif( $key == "olcu" ){ ?>
                    <div > 
                        <input readonly  id="olcu.<?php echo safe_output($row); ?>"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>
                <?php }elseif( $key == "tolerans" ){ ?>
                    <div > 
                        <input readonly  id="tolerans.<?php echo safe_output($row); ?>"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>   
                <?php }elseif( $key == "alt_limit" ){ ?>
                    <div > 
                        <input readonly id="altLimit.<?php echo safe_output($row); ?>"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>
                <?php }elseif( $key == "ust_limit" ){ ?>
                    <div > 
                        <input readonly id="ustLimit.<?php echo safe_output($row); ?>"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>
                <?php }elseif($key == "olcum" ){ ?> 
                    <div > 
                        <input id="olcum.<?php echo safe_output($row); ?>" onchange="olcumKontrol('<?php echo safe_output($row); ?>')"  type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                    </div>    
                <?php }elseif( $key == "sonuc" ){ ?>
                    <div > 
                        <input readonly id="sonuc.<?php echo safe_output($row); ?>" type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]"  > 
                    </div>
                <?php }else { ?>
                    <input readonly type="text" class="form-control input-sm" value="<?php echo safe_attr($val); ?>" name="form[olcum][<?php echo safe_output($row); ?>][<?php echo safe_output($key); ?>]" > 
                <?php } ?>    
            </div>
        </td>
        <?php } ?>
    <?php } ?>
</tr> 
        
<?php } 
} else { ?>
    <tr>
        <td colspan="11" class="text-center text-muted">
            <em>Ölçüm verileri bulunamadı. Migration işlemi sonrası veri eksik olabilir.</em>
        </td>
    </tr>
<?php } ?>
<tr>
    <td colspan="2">Açıklama:</td>
    <td colspan="9">
        <div class="form-group">
            <textarea class="form-control" name="aciklama" rows="2" id="comment"><?php echo "$item->aciklama " ?></textarea>
    </td>
</tr>
<tr>                                  
    <td>Tarih:</td>
    <td colspan="2">
    <?php echo "$item->tarih " ?>
    </td>
    <td colspan="1">Sonuç:</td>
    <td colspan="2">
        <div class="form-group">
            <select name="sonuc" class="form-control">
                <?php foreach($sonuc_secim as $sonuc_secim_litem) { ?>
                    <?php $sonuc_secim_id = isset($form_error) ? set_value("sonuc_secim") : $item->sonuc; ?>
                    <option
                        <?php echo ($sonuc_secim_litem->id === $sonuc_secim_id) ? "selected" : ""; ?>
                        value="<?php echo safe_attr($sonuc_secim_litem->id); ?>"><?php echo safe_output($sonuc_secim_litem->adi); ?></option>
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

function sonucKontrol() {
<?php 
// Migration sonrası JSON verisi null olabilir - JavaScript'te de kontrol ekle
if ($json && isset($json->olcum) && is_array($json->olcum)) {
    foreach($json->{'olcum'} as $row => $d) {?>
    
    <?php echo safe_output($row); ?>=document.getElementById("sonuc.<?php echo safe_output($row); ?>" );

        if (<?php echo safe_output($row); ?>.value == "Geçti") {
            <?php echo safe_output($row); ?>.style.backgroundColor = "green";
            <?php echo safe_output($row); ?>.style.color = "white";
        }
        else if(<?php echo safe_output($row); ?>.value == "Kaldı") { 
           
            <?php echo safe_output($row); ?>.style.backgroundColor = "red";
            <?php echo safe_output($row); ?>.style.color = "white";
        }     
    <?php } 
} ?>

}

</script>