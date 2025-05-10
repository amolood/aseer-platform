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
                            <input type="text" class="form-control @error('make') is-invalid @enderror"
                                   name="make" value="{{ old('make', $car->make) }}" required>
                            @error('make')
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
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror"
                                   name="plate_number" value="{{ old('plate_number', $car->plate_number) }}">
                            @error('plate_number')
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

                        <div class="col-md-6 mb-3">
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
                                   name="missing_date" value="{{ old('missing_date', $car->missing_date ? \Carbon\Carbon::parse($car->missing_date)->format('Y-m-d') : '') }}">
                            @error('missing_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">الحالة *</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
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
                                        <div class="col-md-4 mb-3">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $photo->path) }}"
                                                     class="img-fluid rounded" alt="صورة السيارة">
                                                <div class="form-check position-absolute" style="top: 10px; right: 10px;">
                                                    <input class="form-check-input" type="radio"
                                                           name="featured_photo" value="{{ $photo->id }}"
                                                           {{ $photo->is_featured ? 'checked' : '' }}>
                                                    <label class="form-check-label">الصورة الرئيسية</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
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
@endsection
