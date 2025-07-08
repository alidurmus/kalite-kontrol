<div class="demo ">
    <style type="text/css" media="print">
        @page {
           /* size: auto;   /* auto is the initial value */
            size: 90mm 50mm;
            margin: 0 !important;  /* this affects the margin in the printer settings */
            float: none;
          
        }
    </style>

    <style>
    .etiket-table {
        display: table;
        border: 2 solid;
    }
    h2{
        font-size:4vw;
    }
    .cerceve {
        font-size:2.5vw;
    }

    .cerceve > div {
       
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
        padding: 2px;
    }

    .tanitim-karti tr:nth-child(even) {
       
    }

    .tanitim-karti tr:hover {
        background-color: #ddd;
    }

    .tanitim-karti th {
      
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
    </style>
     <div class="cerceve">
        <table id="" class=" etiket-table tanitim-karti">
            <tbody>
                <tr>
                    <td colspan="4"> <h2>HAMMADDE KABUL ETİKETİ</h2></td>
                </tr>
                <tr>
                    <td>Satıcı Firma Adı</td>
                    <td colspan="3">SERSİM</td>
                </tr>
                <tr>
                    <td>Malzeme Adı</td>
                    <td colspan="3">0,40*398 MM ALÜMİNİZE SAC</td>
                </tr>
                <tr>
                    <td>Parti Numarası</td>
                    <td colspan="3">007 </td>
                </tr>
                <tr>
                    <td>Kontrol Eden</td>
                    <td colspan="3">191050</td>
                </tr>
                <tr>
                    <td>Miktar/Birim</td>
                    <td colspan="3">501</td>
                </tr>
                <tr>
                    <td>Girdi Kontrol Tarihi</td>
                    <td colspan="3">19.10.2019</td>
                </tr>
                <tr>
                    <td>Girdi Kontrol No</td>
                    <td colspan="3">201</td>
                </tr>
                <tr>
                    <td>Açıklama</td>
                    <td colspan="3">açıklama</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $this->load->view("includes/yazdir_buton"); ?>