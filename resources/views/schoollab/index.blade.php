
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $config['title'] }} | School Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/laboratories">School Laboratory Management</a>
    </div>
</nav>

<div class="container mt-4">
    <h2>School Laboratory Management System</h2>
    <p class="text-muted">Manage your laboratories, equipment, students, reservations, and maintenance.</p>

    <div class="mb-3">
        @foreach (['laboratories' => 'Laboratories', 'equipment' => 'Equipment',
        'students' => 'Students', 'reservations' => 'Reservations',
        'maintenance' => 'Maintenance'] as $key => $label)
            <a href="{{ route('schoollab.index', $key) }}"
               class="btn btn-sm {{ $module === $key ? 'btn-primary' : 'btn-outline-primary' }} mb-1">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $config['title'] }}</h5>
            <a href="{{ route('schoollab.create', $module) }}" class="btn btn-success">
                + Add New
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        @foreach($config['fields'] as $field)
                            <th>{{ ucwords(str_replace('_', ' ', $field)) }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            @foreach($config['fields'] as $field)
                                <td>{{ $record->$field }}</td>
                            @endforeach
                            <td>
                                <a href="{{ route('schoollab.edit', [$module, $record->id]) }}"
                                   class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('schoollab.destroy', [$module, $record->id]) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($config['fields']) + 2 }}"
                                class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
