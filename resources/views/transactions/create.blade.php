@extends('layouts.app')

@section('content')
    <!-- CSS Library TomSelect untuk Search Dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <div style="display: flex; gap: 20px;">

        <!-- AREA KIRI: Memilih Produk (Lebar 40%) -->
        <div style="flex: 4; border-right: 1px solid #e2e8f0; padding-right: 20px;">
            <h2 style="margin-top: 0;">Pilih Produk</h2>

            <label>Pilih Barang:</label>
            <select id="product_selector">
                <option value="">-- Pilih Barang --</option>
                @foreach($products as $p)
                    <!-- Ingat: data-price merekam harga barang agar bisa dibaca Vanilla JS -->
                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}">
                        {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>

            <button type="button" class="btn" style="width: 100%; margin-top: 10px; background-color: #10b981;"
                onclick="addToCart()">
                + Tambah ke Keranjang
            </button>
        </div>

        <!-- AREA KANAN: Keranjang & Detail Pembayaran (Lebar 60%) -->
        <div style="flex: 6;">
            <h2 style="margin-top: 0;">Keranjang Belanja</h2>

            <form action="/transaksi" method="POST">
                @csrf

                <!-- Tabel Keranjang Beli -->
                <table style="margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th style="padding: 10px; border-bottom: 2px solid #ccc;">Barang</th>
                            <th style="padding: 10px; border-bottom: 2px solid #ccc;">Harga</th>
                            <th style="padding: 10px; border-bottom: 2px solid #ccc; width: 80px;">Qty</th>
                            <th style="padding: 10px; border-bottom: 2px solid #ccc;">Subtotal</th>
                            <th style="padding: 10px; border-bottom: 2px solid #ccc;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cart_body">
                        <!-- Baris tabel ini akan diisi menggunakan Javascript -->
                    </tbody>
                </table>

                <!-- Rekap Transaksi -->
                <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px;">
                    <div
                        style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; font-size: 20px;">
                        <span>Total Keseluruhan:</span>
                        <span>Rp <span id="display_total_harga">0</span></span>
                    </div>

                    <!-- Input ini yang akan dikirim ke Backend PHP -->
                    <input type="hidden" name="total_harga" id="total_harga" value="0">

                    <label>Uang Bayar (Rp):</label>
                    <input type="number" name="uang_bayar" id="uang_bayar" required min="0" oninput="calculateChange()">

                    <label>Uang Kembali (Rp):</label>
                    <input type="number" name="uang_kembali" id="uang_kembali" readonly style="background-color: #e2e8f0;">

                    <button type="submit" class="btn"
                        style="width: 100%; margin-top: 15px; font-size: 16px; background-color: #4f46e5;">
                        Simpan Transaksi 💾
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= VANILLA JAVASCRIPT ================= -->
    <!-- JS Library TomSelect -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Sihir untuk membuat Dropdown menjadi Searchable
        new TomSelect("#product_selector", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Menyimpan state dari keranjang ke dalam array JavaScript
        let cart = [];

        // Fungsi 1: Menambah barang ke keranjang
        function addToCart() {
            const selector = document.getElementById('product_selector');
            // Sedikit penyesuaian agar lebih kompatibel dengan TomSelect
            const selectedOption = selector.querySelector('option[value="' + selector.value + '"]');

            // Mencegah tambah jika belum memilih barang (value kosong)
            if (!selector.value) {
                alert("Harap pilih barang terlebih dahulu!");
                return;
            }

            const id = selectedOption.value;
            const name = selectedOption.getAttribute('data-name');
            const price = parseFloat(selectedOption.getAttribute('data-price'));

            // Cek apakah barang dengan ID ini sudah ada di dalam array cart
            const existingItemIndex = cart.findIndex(item => item.id === id);

            if (existingItemIndex > -1) {
                // Jika ada, cukup tambahkan quantity-nya
                cart[existingItemIndex].qty += 1;
            } else {
                // Jika belum ada, buat objek baru ke array
                cart.push({ id: id, name: name, price: price, qty: 1 });
            }

            renderCart(); // Perbarui tampilan tabel HTML
        }

        // Fungsi 2: Merender ulang tabel keranjang setiap kali ada perubahan
        function renderCart() {
            const cartBody = document.getElementById('cart_body');
            cartBody.innerHTML = ""; // Bersihkan tabel terlebih dahulu

            let grandTotal = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.qty;
                grandTotal += subtotal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                ${item.name}
                                <!-- TRICK RAHASIA: Input hidden array (name="..[]") khusus untuk ditangkap oleh PHP nanti -->
                                <input type="hidden" name="product_id[]" value="${item.id}">
                                <input type="hidden" name="harga_satuan[]" value="${item.price}">
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">Rp ${item.price.toLocaleString('id-ID')}</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <input type="number" name="qty[]" value="${item.qty}" min="1" 
                                       style="margin:0; padding:5px;" 
                                       onchange="updateQty(${index}, this.value)">
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">Rp ${subtotal.toLocaleString('id-ID')}</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <button type="button" style="background:#ef4444; color:white; border:none; padding:5px 10px; cursor:pointer;" 
                                        onclick="removeFromCart(${index})">X</button>
                            </td>
                        `;
                cartBody.appendChild(tr);
            });

            // Update Total Keseluruhan
            document.getElementById('total_harga').value = grandTotal;
            document.getElementById('display_total_harga').innerText = grandTotal.toLocaleString('id-ID');

            calculateChange(); // Panggil kalkulator kembalian agar otomatis refresh
        }

        // Fungsi 3: Mengubah Quatity langsung dari input keranjang
        function updateQty(index, newQty) {
            cart[index].qty = parseInt(newQty);
            renderCart();
        }

        // Fungsi 4: Hapus baris barang
        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        // Fungsi 5: Kalkulator otomatis uang bayar & kembali
        function calculateChange() {
            const totalHarga = parseFloat(document.getElementById('total_harga').value) || 0;
            const uangBayar = parseFloat(document.getElementById('uang_bayar').value) || 0;

            let kembali = uangBayar - totalHarga;
            // Jangan tampilkan minus jika kurang uang
            if (kembali < 0) { kembali = 0; }

            document.getElementById('uang_kembali').value = kembali;
        }
    </script>
@endsection