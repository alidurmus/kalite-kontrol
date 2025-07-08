<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
    <div class="widget">
            <div class="widget-body">
                <form action="<?php echo base_url("etiket/kutu_no/"); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Lot No</label>
                        <input class="form-control" placeholder="adi" name="lot_no">                        
                    </div>
                    <div class="form-group">
                        <label>Adet</label>
                        <input class="form-control" placeholder="kodu" name="adet">                        
                    </div>
                    
                    </div>
                   
                    <button type="submit" class="btn btn-primary btn-md btn-outline">Gönder</button>
                  
                </form>
            </div><!-- .widget-body -->
        </div><!-- .widget -->
    </div>
</body>
</html>