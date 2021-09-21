<div class="table-responsive">
    <table class="table table-border table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Jumlah Point</th>
                <th>Tanggal Peroleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($user_perolehan->point()->latest()->get() as $value)
                <tbody>
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $value->pivot->total }}</td>
                        <td>{{ Carbon::parse($value->pivot->created_at)->isoFormat("dddd, D MMMM Y") }}</td>
                    </tr>
                </tbody>
            @empty

            @endforelse
        </tbody>
    </table>
</div>
