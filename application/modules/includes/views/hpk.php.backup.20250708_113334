<?php  if ($hpk != NULL) {?>
<table  class="table table-hover table-striped table-bordered content-container">
    <tr>
        <th>hpk</th>
        <th>Lastname</th>
    </tr>
   
    <?php foreach($hpk->{'hpk'} as $row => $d) {?>
        <tr>
        <?php foreach($d as $key => $val) {?>
                    <?php if( $key == "kod" ){ ?>
                        <td>
                         <?php echo $val; ?>
                        </td>
                    <?php } ?>
                    <?php if( $key == "id" ){ ?>
                        <td>
                           <?php echo $val; ?>
                            <a href="<?php echo base_url("anasayfa/proseskontrol_duzenle"); ?>/<?php echo $val ?>" class="btn btn-info">Düzenle</a>
                            <button
                            data-url="<?php echo base_url("anasayfa/proseskontrol_sil/$val") ?>"
                            class="btn btn-sm btn-danger btn-outline remove-btn">
                            <i class="fa fa-trash"></i> Sil
                            </button>
                            </td>
                    <?php } ?>
        <?php } ?>
        </tr>""
    <?php } ?>
</table>


<?php } ?>
