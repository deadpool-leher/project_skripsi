<h2>Pesanan Saya</h2>

@foreach($orders as $order)
<div style="border:1px solid #ccc; padding:15px; margin:10px;">
    
    <strong>#ES-00{{ $order->id }}</strong><br>
    Produk: {{ $order->produk }}<br>
    Total: Rp {{ $order->total }}<br>

    Status:
    <b>
        @if($order->status == 'baru') Menunggu Konfirmasi
        @elseif($order->status == 'diproses') Sedang Diproses
        @elseif($order->status == 'siap') Siap Diambil
        @elseif($order->status == 'selesai') Selesai
        @endif
    </b>

</div>
@endforeach