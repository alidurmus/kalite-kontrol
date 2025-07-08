    <div class="col-md-12">
        <form action="<?php echo base_url("anasayfa/finalkontrol_guncelle/$item->id"); ?>" method="post" enctype="multipart/form-data">
            <table class="table table-striped table-sm">
                <tbody>
                    <tr>
                        <td colspan="4">
                            <h4> Final Kontrol Formu Düzenleme </h4>
                        </td>
                        <td></td>
                        <td colspan="2">Kontrol Nr:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <?php echo "$item->kontrol_no " ?>
                            </div>
                        </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td colspan="1">Ürün Adı:</td>
                        <td colspan="4">
                            <div class="input-group">
                                <input readonly type="text" name="urun_adi" class="form-control input-sm" value="<?php echo "$urun->adi " ?>" placeholder="urun" style="
    width: 25em;">
                            </div>
                        </td>

                        <td colspan="2">Kutu Nr:</td>
                        <td colspan="2">
                            <div class="input-group">
                                <input id="kutu_no" type="text" required class="form-control input-sm" name="kutu_no" value="<?php echo "$item->kutu_no " ?>" placeholder="0000">
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
                                <input type="text" class="form-control input-sm" required name="lot" value="<?php echo "$item->lot " ?>" placeholder="0000">
                                <a href="<?php echo base_url("finalkontrol/search/$item->lot"); ?>">lot</a>
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
                                <input type="text" class="form-control input-sm" required name="klips_montaj_lot" value="<?php echo "$item->klips_montaj_lot " ?>" placeholder="0000">
                                <a href="<?php echo base_url("finalkontrol/search/$item->klips_montaj_lot"); ?>">klips_montaj_lot</a>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2"> Brülör Lot No: </td>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" required name="brulor_lot" value="<?php echo "$item->brulor_lot " ?>" placeholder="0000">
                                <a href="<?php echo base_url("finalkontrol/search/$item->brulor_lot"); ?>">brulor_lot</a>
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
                    <?php $this->load->view("includes/olcum_duzenle"); ?>
                </tbody>
            </table>
            <input type="hidden" name="urun" class="form-control input-sm" value="<?php echo "$urun->id " ?>">
            <input type="hidden" name="tarih" class="form-control input-sm" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="kullanici" class="form-control input-sm" value="<?php echo $user->id ?>" />
            <button type="submit" class="btn btn-primary btn-md btn-outline">Kaydet</button>
            <a href="<?php echo base_url("anasayfa/finalkontrol"); ?>" class="btn btn-md btn-danger btn-outline">İptal</a>
            <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal">Teknik Resim</button>
        </form>

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body">

                        <embed src="<?php echo base_url("uploads/pdf"); ?>/<?php echo "$urun->pdf " ?>" type="application/pdf" frameborder="0" width="100%" height="800px">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div><!-- END column -->


    </div>