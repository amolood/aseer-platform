@extends('layouts.user')

@section('user-content')
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4"><i class="fas fa-edit me-2 text-primary"></i> تعديل بيانات السيارة</h5>

                <form action="{{ route('user.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الماركة *</label>
                            <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                   name="manufacturer" value="{{ old('manufacturer', $car->manufacturer) }}" required>
                            @error('manufacturer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الموديل *</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror"
                                   name="model" value="{{ old('model', $car->model) }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">سنة الصنع</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror"
                                   name="year" value="{{ old('year', $car->year) }}"
                                   min="1900" max="{{ date('Y') + 1 }}">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">رقم اللوحة</label>
                            <input type="text" class="form-control @error('license_plate') is-invalid @enderror"
                                   name="license_plate" value="{{ old('license_plate', $car->license_plate) }}">
                            @error('license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">اللون</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror"
                                   name="color" value="{{ old('color', $car->color) }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">الموقع</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   name="location" value="{{ old('location', $car->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الفقدان</label>
                            <input type="date" class="form-control @error('missing_date') is-invalid @enderror"
                                   name="missing_date" value="{{ old('missing_date', $car->missing_date ? $car->missing_date->format('Y-m-d') : '') }}">
                            @error('missing_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة *</label>
                            <select class="form-control form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="missing" {{ old('status', $car->status) == 'missing' ? 'selected' : '' }}>مفقودة</option>
                                <option value="found" {{ old('status', $car->status) == 'found' ? 'selected' : '' }}>تم العثور عليها</option>
                                <option value="stolen" {{ old('status', $car->status) == 'stolen' ? 'selected' : '' }}>مسروقة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المالك</label>
                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror"
                                   name="owner_name" value="{{ old('owner_name', $car->owner_name) }}">
                            @error('owner_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">معلومات الاتصال بالمالك</label>
                            <input type="text" class="form-control @error('owner_contact') is-invalid @enderror"
                                   name="owner_contact" value="{{ old('owner_contact', $car->owner_contact) }}">
                            @error('owner_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      name="description" rows="3">{{ old('description', $car->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      name="notes" rows="3">{{ old('notes', $car->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">صور السيارة</label>
                            <input type="file" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror"
                                   name="photos[]" multiple accept="image/*">
                            <small class="text-muted d-block mt-1">يمكنك اختيار أكثر من صورة</small>
                            @error('photos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($car->photos->count() > 0)
                            <div class="col-12 mb-4">
                                <label class="form-label">الصور الحالية</label>
                                <div class="row">
                                    @foreach($car->photos as $photo)
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                            <div class="card">
                                                <img src="{{ asset('storage/public/' . $photo->path) }}" class="card-img-top" alt="صورة للسيارة">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="featured_photo"
                                                               value="{{ $photo->id }}" id="photo_{{ $photo->id }}"
                                                               @checked($photo->is_featured)>
                                                        <label class="form-check-label" for="photo_{{ $photo->id }}">
                                                            الصورة الرئيسية
                                                        </label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100"
                                                            onclick="deletePhoto({{ $photo->id }})">
                                                        <i class="fas fa-trash-alt me-1"></i> حذف
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('user.cars.show', $car->id) }}" class="btn btn-light">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function deletePhoto(id) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                const photoCard = document.querySelector(`button[onclick="deletePhoto(${id})"]`).closest('.col-sm-6');

                fetch(`/admin/cars/photo/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        photoCard.remove();
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الصورة');
                });
            }
        }
    </script>
@endsection
