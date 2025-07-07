<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Complaints Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Department</th>
                <th>Title</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $complaint['id'] }}</td>
                    <td>{{ $complaint['student'] }}</td>
                    <td>{{ $complaint['department'] }}</td>
                    <td>{{ $complaint['title'] }}</td>
                    <td>{{ ucfirst($complaint['status']) }}</td>
                    <td>{{ $complaint['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>