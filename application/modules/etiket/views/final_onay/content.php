<div class="demo row">
    <style type="text/css" media="print">
        @page {
           /* size: auto;   /* auto is the initial value */
            size: 210mm 297mm;
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>

    <style>
    .etiket-table {
        display: table;
        border: 2 solid;
    }

    .cerceve {
        flex-direction: row;
        flex-wrap: wrap;
        font-size:2vw;
    }

    .cerceve > div {
        margin: 10px;
        text-align: center;	
    }

    .tanitim-karti {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .tanitim-karti td,
    .tanitim-karti th {
        border: 1px solid #000000;
        padding: 8px;
    }

    .tanitim-karti tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .tanitim-karti tr:hover {
        background-color: #ddd;
    }

    .tanitim-karti th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
    </style>
    <div class="cerceve" style="flex-wrap: nowrap; display: flex; flex-direction: row;">
        <div class="col-md-4" >
            <table id="" class=" etiket-table tanitim-karti">
                <tbody>
                    <tr>
                        <td> 
                        Lot Numarası
                        </td>  
                        <td> 
                        Lot Numarası
                        </td>                  
                    </tr>
                    <tr>
                        <td> 
                        Kutu  No
                        </td>  
                        <td> 
                        Kutu  No
                        </td>                  
                    </tr>
                    <tr style="height: 101px;">
                        <td colspan="2" class="text-center">
                        <?php echo '<img src="'.base_url().'final_kontrol.png" />'  ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!-- END column -->  
        <div class="col-md-4" >   
            <table id="" class=" etiket-table tanitim-karti">
                <tbody>
                    <tr>
                        <td> 
                        Lot Numarası
                        </td>  
                        <td> 
                        Lot Numarası
                        </td>                  
                    </tr>
                    <tr>
                        <td> 
                        Kutu  No
                        </td>  
                        <td> 
                        Kutu  No
                        </td>
                    </tr>
                    <tr style="height: 101px;">
                        <td colspan="2" class="text-center"> 
                        <?php echo '<img src="'.base_url().'final_kontrol.png" />'  ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!-- END column -->  
        <div class="col-md-4" >   
            <table id="" class=" etiket-table tanitim-karti">
                <tbody>
                    <tr>
                        <td> 
                        Lot Numarası                    
                        </td>  
                        <td> 
                        Lot Numarası                    
                        </td>                  
                    </tr>
                    <tr>
                        <td> 
                        Kutu  No                   
                        </td>  
                        <td> 
                        Kutu  No                    
                        </td>                  
                    </tr>
                    <tr style="height: 101px;">
                        <td colspan="2" class="text-center"> 
                        <?php echo '<img src="'.base_url().'final_kontrol.png" />'  ?>
                        
                        </td>                   
                    </tr>
                </tbody>
            </table>
        </div><!-- END column -->  
        <div class="col-md-4" >   
            <table id="" class=" etiket-table tanitim-karti">
                <tbody>
                    <tr>
                        <td> 
                        Lot Numarası                    
                        </td>  
                        <td> 
                        Lot Numarası                    
                        </td>                  
                    </tr>
                    <tr>
                        <td> 
                        Kutu  No                   
                        </td>  
                        <td> 
                        Kutu  No                    
                        </td>                  
                    </tr>
                    <tr style="height: 101px;">
                        <td colspan="2" class="text-center"> 
                        <?php echo '<img src="'.base_url().'final_kontrol.png" />'  ?>
                        
                        </td>                   
                    </tr>
                </tbody>
            </table>
        </div><!-- END column -->  
    </div>
</div>
<?php $this->load->view("includes/yazdir_buton"); ?>