<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>No STT</th>
            <th>Subarea</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $detail)
            <tr>
                <td>{{ $detail->id }}</td>
                <td>{{ $detail->no_stt }}</td>
                <td>{{ $detail->subarea_nama }}</td>
            </tr>
        @endforeach
    </tbody>
</table>