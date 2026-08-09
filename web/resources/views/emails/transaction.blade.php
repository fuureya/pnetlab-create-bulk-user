<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Transaksi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">

    <h2 style="color: #0F0F0F;">Halo, {{ $transaction->user->name }}!</h2>
    
    <p>Terima kasih telah menggunakan Meraki Labs. Berikut adalah update status untuk pesanan Anda:</p>
    
    <div style="background-color: #F8F8F8; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <p><strong>Order ID:</strong> {{ $transaction->order_id }}</p>
        <p><strong>Paket:</strong> {{ $transaction->product->name }}</p>
        <p><strong>Total Pembayaran:</strong> Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> 
            @if($transaction->status === 'success')
                <span style="color: #2BA640; font-weight: bold;">Berhasil</span>
            @elseif($transaction->status === 'pending')
                <span style="color: #FB8C00; font-weight: bold;">Menunggu Pembayaran</span>
            @else
                <span style="color: #c5221f; font-weight: bold;">Gagal/Kedaluwarsa</span>
            @endif
        </p>
    </div>

    @if($transaction->status === 'success')
        <h3 style="color: #065FD4;">Kredensial Voucher Anda:</h3>
        @if($transaction->vouchers->count() > 0)
            @foreach($transaction->vouchers as $voucher)
            <div style="background-color: #e6f4ea; padding: 15px; border-radius: 8px; border: 1px solid #2BA640; margin-bottom: 10px;">
                <p><strong>Username:</strong> {{ $voucher->username }}</p>
                <p><strong>Password:</strong> {{ $voucher->password }}</p>
                <p>Silakan aktivasi voucher Anda di halaman dashboard atau menu Aktivasi Voucher.</p>
            </div>
            @endforeach
        @else
            <p style="color: #c5221f;">Voucher sedang diproses. Silakan cek dashboard Anda secara berkala.</p>
        @endif
    @elseif($transaction->status === 'pending')
        <p>Selesaikan pembayaran Anda segera melalui dashboard <a href="{{ url('/riwayat-transaksi') }}">Riwayat Transaksi</a>.</p>
    @else
        <p>Transaksi ini telah dibatalkan atau melewati batas waktu pembayaran. Silakan buat pesanan baru jika Anda masih berminat.</p>
    @endif

    <p style="margin-top: 30px; font-size: 12px; color: #606060;">Salam,<br>Tim Meraki Labs</p>

</body>
</html>
