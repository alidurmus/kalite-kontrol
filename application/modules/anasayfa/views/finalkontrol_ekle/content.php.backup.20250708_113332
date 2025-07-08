<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Final Kontrol Formu Ekle
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <form action="<?php echo base_url("anasayfa/finalkontrol_kaydet"); ?>" method="post" enctype="multipart/form-data">
            <table class="table table-striped table-sm">
                <tbody>
                    <tr>
                        <td colspan="2">urun Adı:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input readonly type="text" name="urun_adi" class="form-control input-sm" value="<?php echo "$urun->adi " ?>" placeholder="urun">
                            </div>
                        </td>
                        <td></td>
                        <td colspan="2">Kutu Nr:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input id="kutu_no" type="text" required class="form-control input-sm required" required name="kutu_no" value="0" placeholder="0000">
                            </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td colspan="2"> : </td>
                        <td colspan="2">
                            <div class="form-group">

                            </div><!-- .form-group -->
                        </td>
                        <td>-</td>
                        <td colspan="2">Lot No</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm required" required name="lot" value="0" placeholder="0000">
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"> </td>
                        <td colspan="2">
                            <div class="input-group">

                            </div>
                        </td>
                        <td>-</td>
                        <td colspan="2">Klips Montaj Lot No</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm required" required name="klips_montaj_lot" value="0" placeholder="0000">
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"> Brülör Lot No: </td>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm required" required name="brulor_lot" value="0" placeholder="0000">
                            </div>
                        </td>
                        <td>-</td>
                        <td colspan="2"></td>
                        <td colspan="2">

                        </td>
                        <td></td>
                        <td></td>
                    </tr>


                    <!-- olcum alanı ekleme işlemi -->
                    <?php $this->load->view("includes/olcum"); ?>
                </tbody>
            </table>
            <input type="hidden" name="urun" class="form-control input-sm" value="<?php echo "$urun->id " ?>">
            <input type="hidden" name="tarih" class="form-control input-sm" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="kullanici" class="form-control input-sm" value="<?php echo $user->id ?>" />
            <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
            <a href="<?php echo base_url("anasayfa/finalkontrol"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>

        </form>
    </div><!-- END column -->
</div>