@extends('layouts.main', ['title' => 'Edit Line'])

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Line</h1>
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
                            <form method="post" action="{{ route('line.update', $line->id) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Line Code</label>
                                        <input type="text" class="form-control @error('line_code') is-invalid @enderror"
                                            name="line_code" value="{{ old('line_code', $line->line_code) }}" required>
                                        @error('line_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Line Name</label>
                                        <input type="text" class="form-control @error('line_name') is-invalid @enderror"
                                            name="line_name" value="{{ old('line_name', $line->line_name) }}" required>
                                        @error('line_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Material</label>
                                        <select class="form-control select2 @error('material_id') is-invalid @enderror"
                                            name="material_id" required>
                                            <option value="">-- Select Material --</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    {{ old('material_id', $line->material_id) == $material->id ? 'selected' : '' }}>
                                                    {{ $material->material_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('material_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Cycle Time</label>
                                        <input type="number" min="1"
                                            class="form-control @error('cycle_time') is-invalid @enderror" name="cycle_time"
                                            value="{{ old('cycle_time', $line->cycle_time) }}" required>
                                        @error('cycle_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Target</label>
                                        <input type="number" min="1"
                                            class="form-control @error('target') is-invalid @enderror" name="target"
                                            value="{{ old('target', $line->target) }}" required>
                                        @error('target')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('line.index') }}" class="btn btn-secondary">Back</a>
                                        <button type="submit" class="btn btn-primary">Update</button>
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
