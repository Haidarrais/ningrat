<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Nama</th>
                <th>Level</th>
                <th>Total Repeat Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rank as $value)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $value->user->name }}</td>
                    <td>{{ $value->user->roles->first()->name }}</td>
                    <td>Rp. {{ number_format($value->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
