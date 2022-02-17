<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CSV - Data Enhancer</title>
</head>
<body>
    <div class="container mt-5">
        <form action="{{route('csv')}}" method="post" enctype="multipart/form-data">
            <h3 class="text-center mb-3">Upload CSV</h3>
            @csrf
            <div class="mb-3">
                <label for="resource" class="form-label">Resource</label>
                <select class="form-select" id="resource">
                    <option>agents</option>
                    <option>artworks</option>
                    <option>artwork-types</option>
                    <option>terms</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="csvFile" class="form-label">CSV File</label>
                <input class="form-control" type="file" id="csvFile">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">
                Upload CSV
            </button>
        </form>
    </div>
</body>
</html>
