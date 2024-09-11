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
              </div>
              <div class="col-0">              
              :<br/>
              :<br/>
              :<br/>
              :<br/>
              </div>
              <div class="col-7">              
              {{date('d M Y', strtotime($items->created_at))}}<br/>
              {{$items->customer->name}}<br/>
              {{$items->customer->telp}}<br/>
              {{$items->code}}<br/>              
              </div>
            </div>
          </div>
        </div>
        
        <br/><br/>
        <table class="table table-condensed">
          <thead>
            <tr>              
              <th width="60%">Detail Preorder</th>
              <th width="20%">Nominal DP</th>
              <th width="20%">Estimasi</th>              
            </tr>
          </thead>
          <tbody>            
              <tr>                           
                <td>{{$items->description}}</td>                
                <td>Rp. {{ number_format($items->nominal, 0, ".", ".") }}</td>
                <td>{{$items->estimasi}} Hari</td>                
              </tr>            
          </tbody>
        </table>
        <hr width="100%" size="10px">                
        <br />
        </div>                        
          <small class="text-danger">*<span> Tolong simpan lampiran faktur ini sebagai bukti untuk pengambilan dan pemotongan harga Item Preorder  </span></small>
          
    </div>


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->




</body>
</html>
