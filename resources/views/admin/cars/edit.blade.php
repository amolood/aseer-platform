@extends('layouts.admin')
@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-car me-2"></i> تعديل بيانات السيارة
                        </h5>

                        <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- معلومات السيارة الأساسية --}}
                            <div class="mb-4">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i> المعلومات الأساسية
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">رقم اللوحة</label>
                                        <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror"
                                               value="{{ old('license_plate', $car->license_plate) }}">
                                        @error('license_plate')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">الماركة</label>
                                        <input type="text" name="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror"
                                               value="{{ old('manufacturer', $car->manufacturer) }}" required>
                                        @error('manufacturer')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">الموديل</label>
                                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                                               value="{{ old('model', $car->model) }}" required>
                                        @error('model')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">سنة الصنع</label>
                                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                               value="{{ old('year', $car->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                                        @error('year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">اللون</label>
                                        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                                               value="{{ old('color', $car->color) }}">
                                        @error('color')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">الحالة</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="missing" @selected(old('status', $car->status) === 'missing')>مفقودة</option>
                                            <option value="found" @selected(old('status', $car->status) === 'found')>تم العثور عليها</option>
                                            <option value="stolen" @selected(old('status', $car->status) === 'stolen')>مسروقة</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">الموقع</label>
                                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                               value="{{ old('location', $car->location) }}">
                                        @error('location')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">تاريخ الفقدان</label>
                                        <input type="date" name="missing_date" class="form-control @error('missing_date') is-invalid @enderror"
                                               value="{{ old('missing_date', $car->missing_date ? $car->missing_date->format('Y-m-d') : '') }}">
                                        @error('missing_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- معلومات المالك --}}
                            <div class="mb-4">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i> معلومات المالك
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">اسم المالك</label>
                                        <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                                               value="{{ old('owner_name', $car->owner_name) }}">
                                        @error('owner_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">رقم التواصل</label>
                                        <input type="text" name="owner_contact" class="form-control @error('owner_contact') is-invalid @enderror"
                                               value="{{ old('owner_contact', $car->owner_contact) }}">
                                        @error('owner_contact')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- معلومات إضافية --}}
                            <div class="mb-4">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-file-alt me-2"></i> معلومات إضافية
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $car->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $car->notes) }}</textarea>
                                        @error('notes')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">المصدر</label>
                                        <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
                                               value="{{ old('source', $car->source) }}">
                                        @error('source')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- الصور --}}
                            <div class="mb-4">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-images me-2"></i> الصور
                                </h6>

                                <div class="row g-3 mb-3">
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

                                <div class="mb-3">
                                    <label class="form-label">إضافة صور جديدة</label>
                                    <input type="file" name="photos[]" class="form-control @error('photos') is-invalid @enderror"
                                           multiple accept="image/*">
                                    @error('photos')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">إلغاء</a>
                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
    @endpush
@endsection
