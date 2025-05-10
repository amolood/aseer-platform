@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="fas fa-car me-2"></i> تعديل بيانات السيارة
                        </h4>
                        <a href="{{ route('user.cars.show', $car->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> إلغاء
                        </a>
                    </div>

                    <form action="{{ route('user.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Car Basic Information --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">معلومات السيارة الأساسية</h5>
                            <div class="row g-3">
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
                                    <label class="form-label">رقم اللوحة</label>
                                    <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror"
                                           value="{{ old('license_plate', $car->license_plate) }}">
                                    @error('license_plate')
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
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="missing" @selected(old('status', $car->status) === 'missing')>مفقودة</option>
                                        <option value="found" @selected(old('status', $car->status) === 'found')>تم العثور عليها</option>
                                        <option value="stolen" @selected(old('status', $car->status) === 'stolen')>مسروقة</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Missing Information --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">معلومات الفقدان</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">موقع الفقدان</label>
                                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                           value="{{ old('location', $car->location) }}">
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاريخ الفقدان</label>
                                    <input type="date" name="missing_date" class="form-control @error('missing_date') is-invalid @enderror"
                                           value="{{ old('missing_date', $car->missing_date?->format('Y-m-d')) }}">
                                    @error('missing_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Owner Information --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">معلومات المالك</h5>
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

                        {{-- Additional Information --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">معلومات إضافية</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">الوصف</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                              rows="3">{{ old('description', $car->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">ملاحظات</label>
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                              rows="3">{{ old('notes', $car->notes) }}</textarea>
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

                        {{-- Photos --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">الصور</h5>
                            <div class="row g-3 mb-3">
                                @foreach($car->photos as $photo)
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <div class="card">
                                            <img src="{{ asset($photo->url) }}" class="card-img-top" alt="صورة للسيارة">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="featured_photo"
                                                           value="{{ $photo->id }}" id="photo_{{ $photo->id }}"
                                                           @checked($photo->is_featured)>
                                                    <label class="form-check-label" for="photo_{{ $photo->id }}">
                                                        الصورة الرئيسية
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label">إضافة صور جديدة</label>
                                <input type="file" name="photos[]" class="form-control @error('photos') is-invalid @enderror"
                                       multiple accept="image/*">
                                <div class="form-text">يمكنك اختيار أكثر من صورة. الصورة الأولى ستكون الصورة الرئيسية إذا لم يتم تحديد صورة رئيسية.</div>
                                @error('photos')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('user.cars.show', $car->id) }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
