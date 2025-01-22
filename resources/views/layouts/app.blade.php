<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DigiMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @media (max-width: 768px) {
            .auth {
                padding-top: 10px;
                justify-content: center !important;
            }
            .garis {
                display: none;
            }
        }
        .img-container.watermark {
            position: relative;
            pointer-events: none;
        }

        .img-container.watermark::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px; /* Ubah lebar gambar watermark */
            height: 200px; /* Ubah tinggi gambar watermark */
            background-size: cover; /* Agar gambar menyesuaikan ukuran */
            background-image: url('{{ asset('digimartwm.png') }}'); /* Path gambar watermark */
            pointer-events: none; /* Agar gambar watermark tidak mengganggu interaksi */
        }
        
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    

    <!-- Content -->
    <div class="container my-5">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const totalHargaElement = document.getElementById('totalHarga');

            let totalHarga = 0;

            // Fungsi untuk menghitung total harga berdasarkan checkbox yang dipilih
            const updateTotalHarga = () => {
                totalHarga = 0;
                productCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        totalHarga += parseFloat(checkbox.getAttribute('data-harga'));
                    }
                });
                totalHargaElement.textContent = totalHarga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('IDR', 'Rp. ');
            };

            // Event untuk checkbox "Pilih Semua"
            selectAllCheckbox.addEventListener('change', function () {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateTotalHarga();
            });

            // Event untuk setiap checkbox produk
            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    selectAllCheckbox.checked = [...productCheckboxes].every(cb => cb.checked);
                    updateTotalHarga();
                });
            });

            // Inisialisasi total harga
            updateTotalHarga();
        });
    </script>
</body>
</html>
