<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-lg">
            Müşteriler Listesi
            <div id="app-6">
            <p>{{ message }}</p>
            <input v-model="message">
            </div>
            <div id="app-5">
            {{ message}}            
            </div>
            <div id="app">
              <p>
                Last result: <b>{{ decodedContent }}</b>
              </p>
              <input v-model="decodedContent">

              <p class="error">
                {{ errorMessage }}
              </p>

              <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
            </div>

            <?php   if(isAllowedWriteModule()){ ?>
                <a href="<?php echo base_url("vuejs/new_form"); ?>" class="btn btn-outline btn-primary btn-xs pull-right"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            <?php } ?>
        </h4>
    </div><!-- END column -->
    <div class="col-md-12">
        <div class="widget p-lg">

            <?php if(empty($items)) { ?>

                <div class="alert alert-info text-center">
                    <p>Burada herhangi bir veri bulunmamaktadır. Eklemek için lütfen <a href="<?php echo base_url("vuejs/new_form"); ?>">tıklayınız</a></p>
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
                    <tbody class="sortable" data-url="<?php echo base_url("vuejs/rankSetter"); ?>">
                        <?php foreach($items as $item) { ?>
                            <tr id="ord-<?php echo $item->id; ?>">
                                <td class="order"><i class="fa fa-reorder"></i></td>
                                <td class="w50 text-center">#<?php echo $item->id; ?></td>
                                <td><?php echo $item->adi; ?></td>
                                <td><?php echo $item->kodu; ?></td>
                                <td class="text-center w200">
                                    <?php   if(isAllowedDeleteModule()){ ?>
                                        <button
                                            data-url="<?php echo base_url("vuejs/delete/$item->id"); ?>"
                                            class="btn btn-sm btn-danger btn-outline remove-btn">
                                            <i class="fa fa-trash"></i> Sil
                                        </button>
                                    <?php } ?> 
                                    <?php   if(isAllowedUpdateModule()){ ?>
                                        <a href="<?php echo base_url("vuejs/update_form/$item->id"); ?>" class="btn btn-sm btn-info btn-outline"><i class="fa fa-pencil-square-o"></i> Düzenle</a>
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

<script>
    new Vue({
      el: '#app',

      data() {
        return {
          decodedContent: '',
          errorMessage: ''
        }
      },

      methods: {
        onDecode(content) {
          this.decodedContent = content
        },

        onInit(promise) {
          promise.then(() => {
              console.log('Successfully initilized! Ready for scanning now!')
            })
            .catch(error => {
              if (error.name === 'NotAllowedError') {
                this.errorMessage = 'Hey! I need access to your camera'
              } else if (error.name === 'NotFoundError') {
                this.errorMessage = 'Do you even have a camera on your device?'
              } else if (error.name === 'NotSupportedError') {
                this.errorMessage = 'Seems like this page is served in non-secure context (HTTPS, localhost or file://)'
              } else if (error.name === 'NotReadableError') {
                this.errorMessage = 'Couldn\'t access your camera. Is it already in use?'
              } else if (error.name === 'OverconstrainedError') {
                this.errorMessage = 'Constraints don\'t match any installed camera. Did you asked for the front camera although there is none?'
              } else {
                this.errorMessage = 'UNKNOWN ERROR: ' + error.message
              }
            })
        }
      }
    })

  </script>

<script>
var app5 = new Vue({
el: '#app-5',
data: {
    message: 'mesaj'
}


}
)
var app6 = new Vue({
  el: '#app-6',
  data: {
    message: 'Hello Vue!'
  }
})
</script>
