<div class = 'col-md-12'>
    <div class = 'widget p-lg'>
  
    <?php if ( empty( $items ) ) {
        ?>

        <div class = 'alert alert-info text-center'>
        <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen <a href = '<?php echo base_url('kontrol_no/new_form'); ?>'>tıklayınız</a></p>
        </div>

        <?php } else { ?>
           
          <!-- Search form (start) -->
          <form method="post" action="<?= base_url() ?>dashboard/" >
                    <input type="text" name="search" value="<?= $search_text ?>"><input type="submit" name="submit" value="Submit">
                </form>
                <br/>
            <table   class = 'table display table-hover table-striped table-bordered content-container '>
                <thead>
                    <tr>               
                        <th class = 'w50'>Kontrol No</th>
                        <th>process_isim</th>
                        <th>parti_no</th>
                        <th>lot_no</th>
                        <th>kutu_no</th>
                        <th>Tarih</th>              
                    </tr>
                </thead>
                <tbody class = '' data-url = '<?php echo base_url('kontrol_no/rankSetter'); ?>'>
                <?php foreach ( $items as $item ) { ?>
                    <tr id = "ord-<?php echo $item->id; ?>">                   
                        <td class = 'w50 text-center'><?php echo $item->id;
                        ?></td>
                        <td><?php echo $item->process_isim;
                        ?></td>
                        <td><?php echo $item->parti_no;
                        ?></td>
                        <td><?php echo $item->lot_no;
                        ?></td>
                        <td><?php echo $item->kutu_no;
                        ?></td>
                        <td><?php echo $item->tarih;
                        ?></td>            
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
                    <?php }
                    ?>
<p><?php echo $links; ?></p>
    </div><!-- .widget -->
</div><!-- END column -->
          
          
  