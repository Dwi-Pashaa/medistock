@extends('layouts.app')

@section('title', 'Buat Penjualan')

@push('css')
@endpush

@section('content')
@include('components.alert.warning')
<form action="{{ route('transaction.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <a href="{{ route('customer') }}" class="btn btn-primary">
                Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="customer_id" class="mb-2">Pilih Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($customer as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="date" class="mb-2">Tanggal Penjualan</label>
                        <input value="{{ date('Y-m-d')}}" type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror">
                        @error('date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="code" class="mb-2">No Invoice Penjualan</label>
                        <input type="text" class="form-control" value="{{ \App\Models\Transaction::generateCode() }}" readonly>
                        <input type="hidden" name="code" value="{{ \App\Models\Transaction::generateCode() }}">
                    </div>
                </div>
            </div>

            <a href="javascript:void(0)" onclick="return openModal()" class="btn btn-primary mb-3">
                Scan Barcode
            </a>

            <a href="javascript:void(0)" id="addRow" class="btn btn-success btn-md mb-3">
                Tambah Product
            </a>

            <div class="table-responsive">
                <table id="productTable" class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Jumlah</th>
                            <th>SubTotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2" id="totalHarga">Rp 0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="reset" class="btn btn-secondary float-start">Reset</button>
            <button type="submit" class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
</form>
@endsection

@push('modal')
<div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reader" style="width: 100%"></div>
                <input type="text" id="input-target" class="form-control mt-3" placeholder="Hasil Scan">
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5Qrcode;
    let isScanning = false;

    function startScanner() {
        if (!isScanning) {
            html5Qrcode = new Html5Qrcode("reader");
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    html5Qrcode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess,
                        onScanFailure
                    ).then(() => {
                        isScanning = true;
                    });
                }
            }).catch(err => console.error("Camera error:", err));
        }
    }

    function stopScanner() {
        if (isScanning && html5Qrcode) {
            html5Qrcode.stop().then(() => {
                html5Qrcode.clear();
                isScanning = false;
            }).catch(err => console.error("Stop failed:", err));
        }
    }

    function openModal() {
        $("#modal-simple").modal('show');
    }

    function onScanSuccess(decodedText) {
        $.ajax({
            url: "{{ route('transaction.getProduct') }}",
            method: "GET",
            data: { code: decodedText },
            dataType: "json",
            success: function(response) {
                const data = response.data;
                if ($(`select[name='product_id[]'] option[value='${data.id}']:selected`).length > 0) {
                    alert("Produk sudah ditambahkan.");
                    return;
                }
                let row = `
                    <tr>
                        <td><select name="product_id[]" class="form-control" required><option value="${data.id}" selected>${data.name}</option></select></td>
                        <td><input type="number" name="qty[]" class="form-control" value="1" min="1"></td>
                        <td><input type="number" class="form-control price" value="${data.harga_jual}" readonly><input type="hidden" class="real-price" value="${data.harga_jual}"></td>
                        <td><a href="javascript:void(0)" class="btn btn-danger btn-md removeRow">Hapus</a></td>
                    </tr>`;
                $('#productTable tbody').append(row);
                updateTotalHarga();
                stopScanner();
                $("#modal-simple").modal('hide');
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function onScanFailure(error) {}

    $('#modal-simple').on('shown.bs.modal', startScanner);
    $('#modal-simple').on('hidden.bs.modal', stopScanner);

    function updateTotalHarga() {
        let total = 0;
        $('#productTable tbody tr').each(function () {
            const qty = parseInt($(this).find('input[name="qty[]"]').val()) || 0;
            const harga = parseFloat($(this).find('.real-price').val()) || 0;
            total += qty * harga;
        });
        $('#totalHarga').text("Rp " + total.toLocaleString('id-ID'));
    }

    $(document).on('click', '#addRow', function () {
        let row = `
            <tr>
                <td>
                    <select name="product_id[]" class="form-control" required>
                        <option value="">Pilih</option>
                        @foreach ($product as $prd)
                            <option value="{{ $prd->id }}">{{ $prd->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="qty[]" class="form-control" value="1" min="1"></td>
                <td><input type="number" class="form-control price" readonly><input type="hidden" class="real-price"></td>
                <td><a href="javascript:void(0)" class="btn btn-danger btn-md removeRow">Hapus</a></td>
            </tr>`;
        $('#productTable tbody').append(row);
    });

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        updateTotalHarga();
    });

    $(document).on("change", 'select[name="product_id[]"]', function () {
        const row = $(this).closest('tr');
        const product_id = $(this).val();
        if (!product_id) return;
        $.ajax({
            url: "{{ route('purchase') }}/getProduct",
            method: "POST",
            dataType: "json",
            data: { product_id: product_id },
            success: function (response) {
                const harga = response.data.harga_jual || 0;
                row.find('.price').val(harga);
                row.find('.real-price').val(harga);
                row.find('.less-price').val(harga);
                updateTotalHarga();
            }
        });
    });

    $(document).on("input", 'input[name="qty[]"]', updateTotalHarga);
</script>
@endpush