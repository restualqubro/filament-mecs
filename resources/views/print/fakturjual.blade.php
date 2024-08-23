<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style type="text/css">
    table {
      max-width: 98%;      
      max-height: 100%;
    }
    body {
      font-family: 'Courier New', Courier, monospace;
      padding: 5px;
      position: relative;
      width: 100%;
      float: center;
      height: 100%;
    }
    table th,
    table td {
      padding: .700em;
      text-align: center;
    }
    table .kop:before {
      content: ': ';
    }
    .left {
      text-align: left;
    }
    table #caption {
      font-size: 1.5em;
      margin: .5em 0 .75em;
    }
    table.border {
      width: 100%;
      border-collapse: collapse
    }

    table.border tbody th, table.border tbody td {
      border: thin solid #464545;
      padding: 2px
    }
    .ttd td, .ttd th {
      padding-bottom: 4em;
    }
  </style>
</head>
<body>
  <div id="printable" class="container">
    <table border="0" cellpadding="0" cellspacing="0" width="485" class="border" style="overflow-x:auto;">
      <thead>
      <tr>
        <td colspan="6" width="485" id="caption"><img src="{{$logo}}" alt=""></td>
      </tr>
      <tr>
        <td colspan="2">Customer</td>
        <td class="left kop">{{$jual[0]['customer']['name']}}</td>
        <td></td>
        <td>Tanggal / Waktu</td>
        <td class="left kop">{{$jual[0]['created_at']}}</td>
      </tr>
      <tr>
        <td colspan="2">FAKTUR</td>
        <td class="left kop">{{$jual[0]['code']}}</td>
        <td></td>
        <td>Kode PO</td>
        <td class="left kop">@if ($jual[0]['preorder_id'] === null)
          Kosong
          @else 
          Tidak Kosong
          @endif</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      </thead>
      <tbody>
        <tr>
          <th>#</th>
          <th colspan="2">NAMA BARANG</th>
          <th>HARGA JUAL</th>
          <th>QUANTITY</th>
          <th>DISCOUNT</th>
          <th>JUMLAH</th>          
        </tr>
        @php $total = 0 @endphp  
        @foreach($items as $item)                  
        <tr>
          <td align="right">1</td>
          <td style="text-align: left" colspan="2">{{$item->stock->product->name}}</td>
          <td align="right">{{number_format($item->stock->product->hjual, 0, '', '.')}}</td>
          <td>{{$item->qty}}</td>
          <td>{{number_format($item->disc, 0, '', '.')}}</td>
          <td>{{number_format($jumlah = $item->qty * ($item->stock->product->hjual - $item->disc), 0, '', '.')}}</td>
          @php $total += $jumlah = $item->qty * ($item->stock->product->hjual - $item->disc) @endphp
        </tr>
        @endforeach
        <tr>
          <th colspan="6"> TOTAL</th>
          <td>{{number_format($total, 0, '', '.')}}</td>          
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr class="ttd">
          <th colspan="2">MECS KOMPUTER</th>          
          <th colspan="2">CUSTOMER</th>
        </tr>
        <tr>
          <td colspan="2">(....................)</td>
          <td colspan="2">{{$jual[0]['customer']['name']}}</td>          
        </tr>
      </tfoot>
    </table>
    </div>
</body>
{{-- <script>
  $('body').on('click',function(){
  //pop_print('printable');
	window.open(document.URL, 'printer');
  });

  function pop_print(id){
    w=window.open('', 'Print_Page', 'scrollbars=yes');
    var myStyle = '<link rel="stylesheet" href="https://codepen.io/dimaslanjaka/pen/mozZPX.css?ver='+window.btoa(Math.random())+'" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css" media="all" />';
    w.document.write(myStyle + $('#'+id).html());
    w.document.close();
    w.print();
    w.close();
  }
</script> --}}
</html>