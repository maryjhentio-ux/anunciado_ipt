
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $record ? 'Edit' : 'Add' }} {{ $config['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 650px">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $record ? 'Edit' : 'Add' }} {{ $config['title'] }}</h4>
        </div>

        <div class="card-body">
            <form method="POST"
                action="{{ $record
                    ? route('schoollab.update', [$module, $record->id])
                    : route('schoollab.store', $module) }}">

                @csrf
                @if($record)
                    @method('PUT')
                @endif

                @foreach($config['fields'] as $field)
                    <div class="mb-3">
                        <label class="form-label">
                            {{ ucwords(str_replace('_', ' ', $field)) }}
                        </label>

                        @php
                            $value = old($field, $record->$field ?? '');
                            $dateFields = ['reservation_date', 'maintenance_date'];
                            $numericFields = [
                                'capacity', 'quantity', 'year_level',
                                'laboratory_id', 'student_id', 'equipment_id'
                            ];
                        @endphp

                        <input
                            name="{{ $field }}"
                            class="form-control"
                            type="{{ in_array($field, $dateFields) ? 'date' : (in_array($field, $numericFields) ? 'number' : 'text') }}"
                            value="{{ $value }}"
                            {{ in_array($field, $numericFields) ? 'min=1' : '' }}
                            required>

                        @error($field)
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('schoollab.index', $module) }}"
                   class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
