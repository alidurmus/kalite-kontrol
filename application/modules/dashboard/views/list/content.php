<div class = 'col-md-12'>
    <div class = 'widget p-lg'>
  
    <?php 
// Load security helper for XSS protection
$this->load->helper('security');
if ( empty( $items ) ) {
        ?>

        <div class = 'alert alert-info text-center'>
        <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen <a href = '<?php echo base_url('kontrol_no/new_form'); ?>'>tıklayınız</a></p>
        </div>

        <?php } else { ?>
           
          <!-- Search form (start) -->
          <form method="post" action="<?= base_url() ?>dashboard/" >
                    <input type="text" name="search" value="<?php echo safe_attr($search_text); ?>"><input type="submit" name="submit" value="Submit">
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
                    <tr id = "ord-<?php echo safe_output($item->id); ?>">                   
                        <td class = 'w50 text-center'><?php echo safe_output($item->id);
                        ?></td>
                        <td><?php echo safe_output($item->process_isim);
                        ?></td>
                        <td><?php echo safe_output($item->parti_no);
                        ?></td>
                        <td><?php echo safe_output($item->lot_no);
                        ?></td>
                        <td><?php echo safe_output($item->kutu_no);
                        ?></td>
                        <td><?php echo safe_output($item->tarih);
                        ?></td>            
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
                    <?php }
                    ?>
<p><?php echo safe_output($links); ?></p>
    </div><!-- .widget -->
</div><!-- END column -->
          
          
  