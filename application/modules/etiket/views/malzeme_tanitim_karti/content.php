<div class="row">
    <div class="demo col-md-12">

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
        font-size:2.5vw;
        }
    
        #tanitim-karti {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #tanitim-karti td,
        #tanitim-karti th {
            border: 1px solid #000000;
            padding: 8px;
        }

        #tanitim-karti tr:nth-child(even) {
        
        }

        #tanitim-karti tr:hover {
            background-color: #ddd;
        }

        #tanitim-karti th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
        <table id="tanitim-karti" class="table etiket-table">
            <tbody>
                <tr>
                    <td>LOGO</td>
                    <td colspan="3"> <h2>MALZEME TANITIM KARTI</h2></td>
                </tr>
                <tr>
                    <td>PARÇA ADI</td>
                    <td colspan="3">CUBİC 60 ALT ÜST KABUK</td>
                </tr>
                <tr>
                    <td>PARÇA KODU</td>
                    <td colspan="3">007 - 008</td>
                </tr>
                <tr>
                    <td>ÜRETİM TARİHİ</td>
                    <td colspan="3">007 - 008</td>
                </tr>
                <tr>
                    <td>LOT NO</td>
                    <td colspan="3">191050</td>
                </tr>
                <tr>
                    <td>MİKTAR</td>
                    <td colspan="3">501</td>
                </tr>
                <tr>
                    <td>PARTİ NUMARASI</td>
                    <td colspan="3">201</td>
                </tr>
                <tr>
                    <td>ÜRETİM İZNİ</td>
                    <td>KONTROL NO</td>
                    <td>K. KONTROL</td>
                    <td>KONTROL NO</td>
                </tr>
                <tr style="height: 171px;">
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>
    </div><!-- END column -->
</div>
<?php $this->load->view("includes/yazdir_buton"); ?>