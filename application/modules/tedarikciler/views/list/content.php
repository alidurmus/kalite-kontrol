<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Tedarikçiler Listesi
            <?php   if(isAllowedWriteModule()){ ?>
                <a href="<?php echo base_url("tedarikciler/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            <?php } ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table">
                        <thead>
                            <tr>
                                <th class="w50">#</th>
                                <th>Tedarikçi</th>
                                <th>Kodu</th>
                                <th>Durumu</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach($items as $item) { ?>

                                <tr>
                                    <td class="w50 text-center">#<?php echo safe_output($item->id); ?></td>
                                    <td><?php echo safe_output($item->adi); ?></td>
                                    <td><?php echo safe_output($item->kodu); ?></td>
                                    <td class="text-center w100">
                                        <input
                                            data-url="<?php echo base_url("tedarikciler/isActiveSetter/$item->id"); ?>"
                                            class="isActive"
                                            type="checkbox"
                                            data-switchery
                                            data-color="#10c469"
                                            <?php echo ($item->isActive) ? "checked" : ""; ?>
                                        />
                                    </td>
                                    <td class="text-center w200">
                                        <?php if(isAllowedViewModule()){ ?>
                                            <a href="<?php echo base_url("tedarikciler/update_form/$item->id"); ?>" class="btn btn-xs btn-default btn-outline"><i class="fa fa-pencil"></i> Düzenle</a>
                                        <?php } ?>
                                        <?php if(isAllowedDeleteModule()){ ?>
                                            <a href="javascript:void(0)" data-url="<?php echo base_url("tedarikciler/delete/$item->id"); ?>" class="btn btn-xs btn-danger btn-outline remove-btn"><i class="fa fa-trash"></i> Sil</a>
                                        <?php } ?>
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div><!-- END column -->
</div>