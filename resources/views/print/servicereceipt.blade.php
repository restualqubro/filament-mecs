<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>App | <?= $title; ?> </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">    
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<style>
table {
  min-height:300px;
}
.card {
  background-color:rgb(223, 223, 223);
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
                <div class="card">
                  <small><br/>Jl. Manggis No 47, Banjarmasin<br/>
                    0851 0042 2583
                  </small>
                </div>                
              </div>              
            </div>
          </div>
          <div class="col-4">
            <h4><b><u>TANDA TERIMA SERVICE</u></b></h4>
          </div>
          <div class="col-4">
            <div class="row">
              <div class="col-3">
                Tanggal<br/>
                Customer<br/>
                Status<br/>
              </div>
              <div class="col-0">
              :<br/>
              :<br/>
              :<br/>
              </div>
              <div class="col-7">              
              {{$items[0]['created_at']}}<br/>
              {{$items[0]['customer']['name']}}<br/>
              {{$items[0]['customer']['telp']}}
              </div>
            </div>
          </div>
        </div>
        
        <br/><br/>
        <div class="row" style="min-height:250px;">
            <div class="col-6">
                <div class="color-palette-box">
                    <div class="card-header">
                        <h4 class="card-title">
                        Unit
                        </h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-5">
                        Unit<br/>
                        Kategori
                        </div>
                        <div class="1">
                        :<br/>
                        :
                        </div>
                        <div class="col-6">
                          {{$items[0]['merk']}} {{$items[0]['seri']}}
                        <br/>
                          {{$items[0]['category']['name']}}
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
              <div class="color-palette-box">
                  <div class="card-header">
                      <h4 class="card-title">
                      Kelengkapan
                      </h4>
                  </div>
                  <div class="card-body">
                  {{$items[0]['kelengkapan']}}
                  </div>
              </div>
            </div>
            <div class="col-6">
              <div class="color-palette-box">
                <div class="card-header">
                    <h4 class="card-title">
                  Keluhan
                    </h4>
                </div>
                <div class="card-body">
                  {{$items[0]['keluhan']}}
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="color-palette-box">
                <div class="card-header">
                    <h4 class="card-title">
                  Keterangan
                    </h4>
                </div>
                <div class="card-body">
                  {{$items[0]['description']}}
                </div>
              </div>
            </div>
        </div>
        <br/><br/>
        <div class="row">        
          <div class="col-4">          
            <span>Harap lakukan pengecekan terhadap Unit yang anda titipkan sebelum meninggalkan toko <b>MECS KOMPUTER</b></span>            
          </div>
          <div class="col-2">
          </div>
          <div class="col-3">
            <div class="text-center">
              Hormat Kami<br/><br/><br/><br/>(Mecs Komputer)
            </div>
          </div>
          <div class="col-3">
            <div class="text-center">
              Customer<br/><br/><br/><br/>(..........................)
            </div>
          </div>
        </div>

          
    </div>


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->




</body>
</html>
