@extends('layouts.main', ['title' => 'Shift'])

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>List Shift</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                                @if (session($msg))
                                    <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                                        {{ session($msg) }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('shift.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                                        Add</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Shift</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable', {
            processing: true,
            serverSide: true,
            ajax: "{{ route('shift.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'align-middle text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'align-middle'
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    className: 'align-middle'
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    className: 'align-middle'
                },
                {
                    data: null,
                    name: 'action',
                    className: 'align-middle text-center',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                        <a href="/shift/edit/${row.id}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}" data-name="${row.name}">
                            Delete
                        </button>
                    `;
                    }
                }
            ]
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Event delegation untuk tombol delete
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-delete')) {
                e.preventDefault();

                let id = e.target.dataset.id;
                let name = e.target.dataset.name;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Shift "${name}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/shift/destroy/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                }
                            }).then(res => res.json())
                            .then(response => {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                        table.ajax.reload()
                                    });
                                } else {
                                    Swal.fire('Gagal', response.message, 'error');
                                }
                            });
                    }
                });
            }
        });
    </script>
@endpush
