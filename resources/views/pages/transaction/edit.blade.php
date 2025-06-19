@extends('layouts.app')

@section('title', 'Edit Penjualan')

@push('css')
@endpush

@section('content')
@include('components.alert.warning')

<form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">
            <a href="{{ route('transaction') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <label for="customer_id" class="mb-2">Pilih Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($customer as $item)
                                <option value="{{$item->id}}" {{ $transaction->customer_id == $item->id ? 'selected' : '' }}>
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="date" class="mb-2">Tanggal Penjualan</label>
                        <input value="{{ $transaction->date }}" type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror">
                        @error('date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label class="mb-2">No Invoice</label>
                        <input type="text" class="form-control" value="{{ $transaction->invoice }}" readonly>
                    </div>
                </div>
            </div>

            <a href="javascript:void(0)" onclick="return openModal()" class="btn btn-primary mb-3">Scan Barcode</a>
            <a href="javascript:void(0)" id="addRow" class="btn btn-success mb-3">Tambah Product</a>

            <div class="table-responsive">
                <table id="productTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->item as $item)
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control" required>
                                        <option value="">Pilih</option>
                                        @foreach ($product as $prd)
                                            <option value="{{ $prd->id }}" {{ $prd->id == $item->product_id ? 'selected' : '' }}>
                                                {{ $prd->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="qty[]" class="form-control" value="{{ $item->qty }}" min="1"></td>
                                <td>
                                    <input type="number" class="form-control price" value="{{ $item->total / $item->qty }}" readonly>
                                    <input type="hidden" class="real-price" value="{{ $item->total / $item->qty }}">
                                </td>
                                <td><a href="javascript:void(0)" class="btn btn-danger btn-md removeRow">Hapus</a></td>
                            </tr>
                        @endforeach
                    </tbody>
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
            <button type="submit" class="btn btn-primary float-end">Update</button>
        </div>
    </div>
</form>
@endsection

@push('modal')
<div class="modal fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                if (devices.length) {
                    html5Qrcode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess,
                        onScanFailure
                    ).then(() => { isScanning = true; });
                }
            });
        }
    }

    function stopScanner() {
        if (isScanning && html5Qrcode) {
            html5Qrcode.stop().then(() => {
                html5Qrcode.clear();
                isScanning = false;
            });
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
                        <td>
                            <select name="product_id[]" class="form-control" required>
                                <option value="${data.id}" selected>${data.name}</option>
                            </select>
                        </td>
                        <td><input type="number" name="qty[]" class="form-control" value="1" min="1"></td>
                        <td>
                            <input type="number" class="form-control price" value="${data.harga_jual}" readonly>
                            <input type="hidden" class="real-price" value="${data.harga_jual}">
                        </td>
                        <td><a href="javascript:void(0)" class="btn btn-danger btn-md removeRow">Hapus</a></td>
                    </tr>`;
                $('#productTable tbody').append(row);
                updateTotalHarga();
                stopScanner();
                $("#modal-simple").modal('hide');
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
                updateTotalHarga();
            }
        });
    });

    $(document).on("input", 'input[name="qty[]"]', updateTotalHarga);

    updateTotalHarga();
</script>
@endpush
