{{-- resources/views/vendor/adminlte/content.blade.php --}}
@section('content')

    <div class="card mt-4 offset-right" style="margin-left: 270px;">
        <div class="card-header">
            <h3 class="card-title">Create Category</h3>
        </div>
        <div class="card-body">
            <form id="create-category-form">
                @csrf

                <input type="hidden" name="id" value="{{ $categoryData['id'] }}">
                <div class="form-group">
                    <label for="URL">URL</label>
                    <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ $categoryData['url'] }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="title">Category Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $categoryData['title'] }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $categoryData['name'] }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ $categoryData['description'] }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">Select Parent Category</option>
                        @foreach($categoryData['list_all_categories'] as $category)
                            <option value="{{ $category['id'] }}"
                                {{ $categoryData['parent_id'] == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}, {{ $category['parent'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content">{{ $categoryData['content'] }}</textarea>
                    @error('content')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="imgs">Images</label>
                    <input type="text" class="form-control @error('imgs') is-invalid @enderror" id="imgs" name="imgs" value="{{ $categoryData['imgs'] }}">
                    @error('imgs')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inactive">Inactive</label>
                    <input type="checkbox" class="form-control @error('inactive') is-invalid @enderror" id="inactive" name="inactive" value="1" style="margin-left: 0; width: 40px;" {{ $categoryData['inactive'] ? 'checked' : '' }}>
                    @error('inactive')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="created_at">Created At</label>
                    <input type="text" class="form-control" id="created_at" name="created_at" value="{{ $categoryData['created_at'] }}" readonly>
                </div>

                <div class="form-group">
                    <label for="updated_at">Updated At</label>
                    <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ $categoryData['updated_at'] }}" readonly>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ ($categoryData['id'] !== 'new') ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('create-category-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            //data.inactive = formData.get('inactive') ? 1 : 0; // Checkbox value handling
            data.inactive = formData.has('inactive');

            const updatedAt = document.querySelector('#updated_at');

            fetch("{{ route('admin.saveCategory', '') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        // Выброс ошибки, если ответ не успешный
                        throw new Error('Ошибка сети: ' + response.status);
                    }
                    return response.json(); // Парсинг JSON из ответа
                })
                .then(data => {
                    if(data.success){
                        // Дополнительные действия при успешной отправке
                        updatedAt.value = data.data.updated_at;

                        const currentUrl = window.location.href;

                        if (currentUrl.endsWith('/new')) {
                            window.location.href = currentUrl.replace('/new', '/' + data.data.id);
                        } else {
                            window.location.reload();
                        }
                    }
                    else{
                        alert('Данные не сохранены');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Обработка ошибок
                });
        });
    </script>
@endsection
