@extends('layouts.app')

@section('content')

<form action="{{route('csv.import.action')}}" method="post" enctype="multipart/form-data">
    <h3 class="text-center mb-3">Import CSV</h3>
    @csrf
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    @if (count($errors ?? []) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mb-3">
        <label for="resource" class="form-label">Resource</label>
        <select class="form-select" id="resource" name="resource">
            @foreach ($resources as $resource)
                <option {{ $resource['selected'] ? 'selected' : '' }}>{{ $resource['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="csvFile" class="form-label">CSV File</label>
        <input class="form-control" type="file" id="csvFile" name="csvFile">
    </div>
    <button type="submit" name="submit" class="btn btn-primary">
        Import CSV
    </button>
</form>

@endsection

