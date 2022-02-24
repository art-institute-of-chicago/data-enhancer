<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CSV - Data Enhancer</title>
    <style>
        .container {
            max-width: 500px;
        }
        dl, ol, ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <form action="{{route('csv.upload')}}" method="post" enctype="multipart/form-data">
            <h3 class="text-center mb-3">Upload CSV</h3>
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
                Upload CSV
            </button>
        </form>
    </div>
</body>
</html>
