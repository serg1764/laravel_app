{{-- resources/views/vendor/adminlte/content.blade.php --}}
@section('content')

    <div class="card mt-4 offset-right" style="margin-left: 270px;">
        <div class="card-header">
            <h3 class="card-title">Create Product</h3>
        </div>
        <div class="card-body">
            <form id="create-product-form">
                @csrf

                <input type="hidden" name="id" value="{{ $phoneData['id'] }}">

                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $phoneData['name'] }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="title">Product Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $phoneData['title'] }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ $phoneData['url'] }}">
                    @error('url')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ $phoneData['description'] }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content">{{ $phoneData['content'] }}</textarea>
                    @error('content')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $phoneData['price'] }}">
                    @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ $phoneData['quantity'] }}">
                    @error('quantity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ $phoneData['sku'] }}">
                    @error('sku')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        <option value="">Select Category</option>
                        @foreach($phoneData['list_all_categories'] as $category)
                            <option value="{{ $category['id'] }}" {{ $phoneData['category_id'] == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ $phoneData['image'] }}">
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ $phoneData['brand'] }}">
                    @error('brand')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inactive">Inactive</label>
                    <input type="checkbox" class="form-control @error('inactive') is-invalid @enderror" id="inactive" name="inactive" value="1" style="margin-left: 0; width: 40px;" {{ $phoneData['inactive'] ? 'checked' : '' }}>
                    @error('inactive')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="created_at">Created At</label>
                    <input type="text" class="form-control" id="created_at" name="created_at" value="{{ $phoneData['created_at'] }}" readonly>
                </div>

                <div class="form-group">
                    <label for="updated_at">Updated At</label>
                    <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ $phoneData['updated_at'] }}" readonly>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ ($phoneData['id'] !== 'new') ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('create-product-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.inactive = formData.has('inactive');

            const updatedAt = document.querySelector('#updated_at');

            fetch("{{ route('admin.saveProduct', '') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network error: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success){
                        updatedAt.value = data.data.updated_at;

                        const currentUrl = window.location.href;

                        if (currentUrl.endsWith('/new')) {
                            window.location.href = currentUrl.replace('/new', '/' + data.data.id);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        alert('Product data not saved');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection

