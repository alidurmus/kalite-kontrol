<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Girdi Kontrol Listesi
            <?php   if(!isAllowedWriteModule()){ ?>
                <a href="<?php echo base_url("girdikontrol/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            <?php } ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget p-lg">

            <?php if(empty($items)) { ?>

                <div class="alert alert-info text-center">
                    <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen <a href="<?php echo base_url("girdikontrol/new_form"); ?>">tıklayınız</a></p>
                </div>

            <?php } else { ?>

                <table class="table table-hover table-striped table-bordered content-container">
                    <thead>
                        <th class="order"><i class="fa fa-reorder"></i></th>
                        <th class="w50">#id</th>
                        <th>Tedarikçi</th>
                        <th>Malzeme</th>
                        <th>İrsaliye</th>
                        <th>Kontrol_no</th>
                        <th>Parti_no</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </thead>
                    <tbody class="sortable" data-url="<?php echo base_url("girdikontrol/rankSetter"); ?>">

                        <?php foreach($items as $item) { ?>

                            <tr id="ord-<?php echo $item->id; ?>">
                                <td class="order"><i class="fa fa-reorder"></i></td>
                                <td class="w50 text-center">#<?php echo $item->id; ?></td>
                                <td><?php echo $item->tedarikci; ?></td>
                                <td><?php echo $item->malzeme; ?></td>
                                <td><?php echo $item->irsaliye; ?></td>
                                <td><?php echo $item->kontrol_no; ?></td>
                                <td><?php echo $item->parti_no; ?></td>
                                <td><?php echo $item->tarih; ?></td>
                                <td class="text-center w200">
                                    <?php   if(!isAllowedDeleteModule()){ ?>
                                        <button
                                            data-url="<?php echo base_url("girdikontrol/delete/$item->id"); ?>"
                                            class="btn btn-sm btn-danger btn-outline remove-btn">
                                            <i class="fa fa-trash"></i> Sil
                                        </button>
                                    <?php } ?> 
                                    <?php   if(!isAllowedUpdateModule()){ ?>
                                        <a href="<?php echo base_url("girdikontrol/update_form/$item->id"); ?>" class="btn btn-sm btn-info btn-outline"><i class="fa fa-pencil-square-o"></i> Düzenle</a>
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            <?php } ?>

        </div><!-- .widget -->
    </div><!-- END column -->
</div>