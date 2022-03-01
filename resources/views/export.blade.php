@extends('layouts.app')

@section('content')

<form action="{{route('csv.export.action')}}" method="post" enctype="multipart/form-data">
    <h3 class="text-center mb-3">Export CSV</h3>
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
    <div class="form-group mb-3">
        <label for="resource">Resource</label>
        <select class="form-select" id="resource" name="resource">
            @foreach ($resources as $resource)
                <option {{ $resource['selected'] ? 'selected' : '' }}>{{ $resource['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="csvFile">Only export the following IDs...</label>
        <textarea class="form-control" id="ids" name="ids" rows="3"></textarea>
        <small class="form-text text-muted">
            Optional, ignored if blank. Separate ids by commas or newlines.
        </small>
    </div>
    <div class="form-group mb-3">
        <label for="csvFile">Only export items that have been updated since...</label>
        <input class="form-control" type="text" id="since" name="since"/>
        <small class="form-text text-muted">
            Optional, ignored if blank. Value will be passed to <a href="https://carbon.nesbot.com/docs/">Carbon::parse()</a>. Specify timezone, if precision is needed.
        </small>
    </div>
    <div class="form-group mb-3">
        <label for="exportWhereBlank">Only export items where any of the following field(s) are blank...</label>
        <div id="container-for-blank-fields">
        </div>
        <small class="form-text text-muted">Optional, ignored if blank.</small>
    </div>
    <div class="mb-3">
        <label for="csvFile">Only include the following fields in the export...</label>
        <div id="container-for-export-fields">
        </div>
        <small class="form-text text-muted">
            Optional; if none are checked, all fields will be exported. The main id field is always included.
        </small>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">
        Export CSV
    </button>
</form>

<script>
function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

function getCheckbox(value, name) {
    let checkbox = document.createElement('div');
    checkbox.classList.add('form-check');

    let inputId = name + '-' + value;

    let input = document.createElement('input');
    input.classList.add('form-check-input');
    input.setAttribute('type', 'checkbox');
    input.setAttribute('id', inputId);
    input.setAttribute('name', name + '[]');
    input.value = value

    let label = document.createElement('label');
    label.classList.add('form-check-label');
    label.setAttribute('for', inputId);
    label.textContent = value;

    checkbox.appendChild(input);
    checkbox.appendChild(label);

    return checkbox;
}

function updateFieldLists() {
    let resourceField = document.querySelector('#resource');
    let containerForBlankFields = document.querySelector('#container-for-blank-fields');
    let containerForExportFields = document.querySelector('#container-for-export-fields');
    let fieldLists = JSON.parse(document.querySelector('#field-lists').textContent);

    removeAllChildNodes(containerForBlankFields);
    removeAllChildNodes(containerForExportFields);

    fieldLists[resourceField.value].forEach(function (fieldName, i) {
        let blankCheckbox = getCheckbox(fieldName, 'blankFields');
        let exportCheckbox = getCheckbox(fieldName, 'exportFields');

        containerForBlankFields.appendChild(blankCheckbox);
        containerForExportFields.appendChild(exportCheckbox);
    });
}

document.querySelector('#resource').addEventListener('change', updateFieldLists);

document.addEventListener('DOMContentLoaded', updateFieldLists);
</script>

<script type="application/json" id="field-lists">
{!! $fieldLists !!}
</script>

@endsection
