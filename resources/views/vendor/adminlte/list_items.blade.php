@section('content')
    @if(isset($phoneData) && !empty($phoneData))
        <div style="margin-left: 300px;">
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Inactive</th>
                </tr>
                </thead>
                <tbody>
                @foreach($phoneData as $index => $phone)
                    <tr style="background-color: {{ $loop->even ? '#AAAA88' : '#CCCCCC' }};">
                        <td><a href="{{ route('admin.getProduct', $phone['id']) }}">{{ $phone['id'] }}</a></td>
                        <td>{{ $phone['brand'] }}</td>
                        <td><a href="{{ route('admin.getProduct', $phone['id']) }}">{{ $phone['name'] }}</a></td>
                        <td>{{ $phone['price'] }}</td>
                        <td>{{ $phone['quantity'] }}</td>
                        <td>{{ $phone['category_id'] }}</td>
                        <td>
                            <label>
                                <input type="checkbox" {{ $phone['inactive'] ? 'checked' : '' }} disabled>
                            </label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No phones available.</p>
    @endif
@endsection
