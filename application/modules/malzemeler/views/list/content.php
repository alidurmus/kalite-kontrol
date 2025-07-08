<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Malzeme Listesi
            <?php 
// Load security helper for XSS protection
$this->load->helper('security');
if (isAllowedWriteModule()) { ?>
                <a href="<?php echo base_url("malzemeler/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            <?php } ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("malzemeler/index"); ?>" method="get">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="search_adi">Malzeme Adı</label>
                            <input type="text" name="search_adi" class="form-control" placeholder="Malzeme adı..." value="<?php echo safe_attr($search_adi); ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="search_kodu">Malzeme Kodu</label>
                            <input type="text" name="search_kodu" class="form-control" placeholder="Malzeme kodu..." value="<?php echo safe_attr($search_kodu); ?>">
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Ara</button>
                            <a href="<?php echo base_url("malzemeler"); ?>" class="btn btn-outline-secondary">Temizle</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="widget p-lg">

            <?php if (empty($items)) { ?>

                <div class="alert alert-info text-center">
                    <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen <a href="<?php echo base_url("malzemeler/new_form"); ?>">tıklayınız</a></p>
                </div>

            <?php } else { ?>

                <table class="table table-hover table-striped table-bordered content-container">
                    <thead>
                        <th class="order"><i class="fa fa-reorder"></i></th>
                        <th class="w50">#id</th>
                        <th>Adı</th>
                        <th>Kodu</th>
                        <th>İşlem</th>
                    </thead>
                    <tbody class="sortable" data-url="<?php echo base_url("malzemeler/rankSetter"); ?>">

                        <?php foreach ($items as $item) { ?>

                            <tr id="ord-<?php echo safe_output($item->id); ?>">
                                <td class="order"><i class="fa fa-reorder"></i></td>
                                <td class="w50 text-center">#<?php echo safe_output($item->id); ?></td>
                                <td><?php echo safe_output($item->adi); ?></td>
                                <td><?php echo safe_output($item->kodu); ?></td>
                                <td class="text-center w200">
                                    <?php if (isAllowedDeleteModule()) { ?>
                                        <button data-url="<?php echo base_url("malzemeler/delete/$item->id"); ?>" class="btn btn-sm btn-danger btn-outline remove-btn">
                                            <i class="fa fa-trash"></i> Sil
                                        </button>
                                    <?php } ?>
                                    <?php if (isAllowedUpdateModule()) { ?>
                                        <a href="<?php echo base_url("malzemeler/update_form/$item->id"); ?>" class="btn btn-sm btn-info btn-outline"><i class="fa fa-pencil-square-o"></i> Düzenle</a>
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>

            <?php } ?>

        </div><!-- .widget -->
        <div class="widget-body">
            <nav aria-label="Page navigation">
                <?php echo safe_output($links); ?>
            </nav>
        </div>
    </div><!-- END column -->
</div>