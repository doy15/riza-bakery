@extends('layouts.main', ['title' => 'Add Material'])

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Material</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Whoops!</strong> Ada kesalahan pada input:<br><br>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <form method="post" action="{{ route('material.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Material Code</label>
                                        <input type="text"
                                            class="form-control @error('material_code') is-invalid @enderror"
                                            name="material_code" value="{{ old('material_code') }}" required>
                                        @error('material_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Material Name</label>
                                        <input type="text"
                                            class="form-control @error('material_name') is-invalid @enderror"
                                            name="material_name" value="{{ old('material_name') }}" required>
                                        @error('material_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control @error('type') is-invalid @enderror" name="type"
                                            required>
                                            <option value="">-- Pilih --</option>
                                            <option value="raw" {{ old('type') == 'raw' ? 'selected' : '' }}>Raw Material
                                            </option>
                                            <option value="finish_good"
                                                {{ old('type') == 'finish_good' ? 'selected' : '' }}>
                                                Finish Good</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Minimum Stock</label>
                                        <input type="number" min="1"
                                            class="form-control @error('minimum_stock') is-invalid @enderror"
                                            name="minimum_stock" value="{{ old('minimum_stock') }}" required>
                                        @error('minimum_stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Stock</label>
                                        <input type="number" min="1"
                                            class="form-control @error('stock') is-invalid @enderror" name="stock"
                                            value="{{ old('stock') }}" required>
                                        @error('stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                            name="unit" value="{{ old('unit') }}" placeholder="kg, liter, pcs, unit"
                                            required>
                                        @error('unit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('material.index') }}" class="btn btn-secondary">Back</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('input[type="number"][min]').forEach(function(input) {
            input.addEventListener('input', function() {
                if (this.value < this.min) {
                    this.value = '';
                }
            });
        });
    </script>
@endpush
