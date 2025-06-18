@extends('layouts.app')

@section('title', 'Kategori Prodcut')

@push('css')
    
@endpush

@section('content')
    <div class="card">
        @can('tambah product')
            <div class="card-header">
                <a href="javascript:void(0)" id="addBtn" class="btn btn-primary">
                    Tambah
                </a>
            </div>
        @endcan
        <div class="card-body">
            <div class="d-flex">
                <div class="text-secondary">
                    <div class="mx-2 d-inline-block">
                        <select name="sort" id="sort" class="form-control">
                            @php
                                $opts = [
                                    10,25,50,100
                                ];
                            @endphp 
                            @foreach ($opts as $opt)
                                <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ms-auto text-secondary">
                    <form>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="search" placeholder="Search for…">
                            <button class="btn" type="submit">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered datatable table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kategori</th>
                        @canany(['edit product', 'hapus product'])
                            <th class="text-center">#</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$item->name}}</td>
                            @canany(['edit product', 'hapus product'])
                                <td class="text-center">
                                    @can('edit product')
                                        <a href="javascript:void(0)" onclick="return editModal('{{$item->id}}')" class="btn btn-warning btn-md">Edit</a>
                                    @endcan
                                    @can('hapus product')
                                        <a href="javascript:void(0)" onclick="return deleteItem('{{$item->id}}')" class="btn btn-danger btn-md">Hapus</a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="3">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">
                Showing <span>{{ $kategori->firstItem() }}</span> 
                to <span>{{ $kategori->lastItem() }}</span> of
                <span>{{ $kategori->total() }}</span> entries
            </p>
            <ul class="pagination m-0 ms-auto">
                {{ $kategori->links() }}
            </ul>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-1 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" id="type">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Kategori</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span class="invalid-feedback error_name"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="storeBtn" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    const BASE = "{{ route('kategori') }}";

    let params = new URLSearchParams(window.location.search);
    $("#sort").change(function() {
        params.set('sort', $(this).val());
        window.location.href = BASE + '?' + params.toString();
    });

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    $("#addBtn").click(function() {
        $("#modal-simple").modal('show')
        $(".modal-title").html("Tambah Kategori");
        $("#name").val("");
        $("#type").val("create");
        $("#id").val("");
    });

    $("#storeBtn").click(function() {
        let id = $("#id").val();
        let type = $("#type").val()
        let name = $("#name").val();

        let url;
        let method;

        if (type === 'create') {
            url = BASE + '/store';
            method = "POST";
        } else {
            url = BASE + `/${id}/update`
            method = "PUT";
        }
        
        $.ajax({
            url: url,
            method: method,
            data: {
                name: name
            },
        }).done(function(response) {
            if (response.errors) {
                $.each(response.errors, function(index, value) {
                    console.log(value);
                    
                    $("#name").addClass('is-invalid');
                    $(".error_" + index).html(value);

                    setTimeout(() => {
                        $("#name").removeClass('is-invalid');
                        $(".error_" + index).html('');
                    }, 3000);
                })                
            } else {
                $("#modal-simple").modal('hide')
                Toast.fire({
                    icon: response.status,
                    title: response.message
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
        });
    });

    function editModal(id) {
        let url = BASE + `/${id}/show`
        $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        }).done(function(response){
            $(".modal-title").html("Edit Kategori");
            let data = response.data;
            $("#modal-simple").modal('show')

            $("#id").val(data.id);
            $("#name").val(data.name);
            $("#type").val("update");
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
        });
    }

    function deleteItem(id) {
        Swal.fire({
            title: "Peringatan !",
            text: "Anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/' + id + '/destroy',
                    method: "DELETE",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: "error",
                            title: "Server Error"
                        });
                    }
                })
            }
        });
    }
</script>
@endpush