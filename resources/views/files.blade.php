@extends('layouts.app')

@section('content')

<h3 class="text-center mb-3">Generated CSV Files</h3>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">File</th>
            <th scope="col">Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($csvFiles as $csvFile)
            <tr>
                <td>{{ $csvFile->updated_at->format('Y-m-d g:i A') }}</th>
                <td><a href="{{ $csvFile->getCsvUrl() }}">{{ $csvFile->filename }}</a></td>
                <td>{{ $csvFile->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p>Files are automatically deleted 72 hours after generation.</p>

@endsection
