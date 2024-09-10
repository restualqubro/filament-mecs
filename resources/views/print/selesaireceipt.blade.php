<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>App | {{ $title; }} </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">    
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<style>
table {
  min-height:200px;
  font-size:0.8rem;
}
.card-header {
  border: 1px solid black;
}
</style>
  
</head>
<body>

    <div class="wrapper">
        <div class="row">
        
          <div class="col-4">
            <div class="row">
              <div class="col-12">
                <img src="{{$logo}}" style="max-width:100px"/>
                <br/>Jl. Manggis No 47, Banjarmasin<br/>
                0851 0042 2583
              </div>              
            </div>
          </div>
          <div class="col-4">
            <h4><b><u>{{$title}}</u></b></h4>
          </div>
          <div class="col-4">
            <div class="row">
              <div class="col-3">
                Tanggal<br/>
                Customer<br/>
                Contact<br/>
                Kode<br/>
                Unit<br/>
              </div>
              <div class="col-0">
              :<br/>
              :<br/>
              :<br/>
              :<br/>
              :<br/>
              </div>
              <div class="col-7">              
              {{date('d M Y', strtotime($items[0]['created_at']))}}<br/>
              {{$items[0]['service']['customer']['name']}}<br/>
              {{$items[0]['service']['customer']['telp']}}<br/>
              {{$items[0]['service']['code']}}<br/>
              {{$items[0]['service']['merk']}}  {{$items[0]['service']['seri']}}<br/>
              </div>
            </div>
          </div>
        </div>
        
        <br/><br/>
        <table class="table table-condensed">
          <thead>
            <tr>
              <th width="5%">#</th>              
              <th width="40%">Keterangan Perbaikan</th>
              <th width="10%">Qty</th>
              <th width="15%">Harga</th>
              <th width="15%">Potongan</th>
              <th width="15%">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sub = 0;
            $totpot = 0;
            $total = 0;
            foreach ($data as $i) {
              $sub += $i['biaya'] * $i['service_qty'];
              $totpot += $i['service_disc'] * $i['service_qty'];              
              $total += $i['service_qty'] * ($i['biaya'] - $i['service_disc']);              
            ?>
              <tr>
                <td>{{ $no++ }}</td>                
                <td>{{ $i['catalog']['name'] }}</td>
                <td>{{ $i['service_qty'] }}</td>
                <td>Rp. {{ number_format($i['biaya'], 0, ".", ".") }}</td>
                <td>Rp. {{ number_format($i['service_disc'], 0, ".", ".") }}</td>
                <td>Rp. {{ number_format($i['service_qty'] * ($i['biaya'] - $i['service_disc']), 0, ".", ".") }}</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <hr width="100%" size="10px">
        <div class="row">
          <div class="col-8">
          </div>
          <div class="col-2">
            <b>Sub Total<br />
              Total Potongan<br />
              Total</b>
          </div>
          <div class="col-2">
            <b>Rp. {{ number_format($sub, 0, ".", ".") }}<br />
              Rp. {{ number_format($totpot, 0, ".", ".") }}<br />
              Rp. {{ number_format($total, 0, ".", ".") }}</b>
          </div>
          <hr width="100%" size="10px">          
          <br />
        </div>                        
          <small class="text-danger">*<span>  Lampiran ini hanya sebagai catatan tim, bukan sebagai pengganti Nota / Faktur Service</span>                    </small>
          
    </div>


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->




</body>
</html>
