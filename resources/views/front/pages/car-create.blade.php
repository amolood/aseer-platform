@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-car me-2"></i> إضافة سيارة مفقودة
                    </h3>

                    <form action="{{ route('front.cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Car Basic Information --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">معلومات السيارة الأساسية</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">الماركة</label>
                                    <input type="text" name="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror"
                                           value="{{ old('manufacturer') }}" required>
                                    @error('manufacturer')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">الموديل</label>
                                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                                           value="{{ old('model') }}" required>
                                    @error('model')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">سنة الصنع</label>
                                    <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                           value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}">
                                    @error('year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">رقم اللوحة</label>
                                    <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror"
                                           value="{{ old('license_plate') }}">
                                    @error('license_plate')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">اللون</label>
                                    <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                                           value="{{ old('color') }}">
                                    @error('color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">الحالة</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="missing" @selected(old('status') === 'missing')>مفقودة</option>
                                        <option value="stolen" @selected(old('status') === 'stolen')>مسروقة</option>
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
                                           value="{{ old('location') }}">
                                    @error('location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاريخ الفقدان</label>
                                    <input type="date" name="missing_date" class="form-control @error('missing_date') is-invalid @enderror"
                                           value="{{ old('missing_date') }}">
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
                                           value="{{ old('owner_name') }}">
                                    @error('owner_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">رقم التواصل</label>
                                    <input type="text" name="owner_contact" class="form-control @error('owner_contact') is-invalid @enderror"
                                           value="{{ old('owner_contact') }}">
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
                                              rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">ملاحظات</label>
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                              rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">المصدر</label>
                                    <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
                                           value="{{ old('source') }}">
                                    @error('source')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Photos --}}
                        <div class="mb-4">
                            <h5 class="text-muted border-bottom pb-2 mb-3">الصور</h5>
                            <div class="mb-3">
                                <label class="form-label">صور السيارة</label>
                                <input type="file" name="photos[]" class="form-control @error('photos') is-invalid @enderror"
                                       multiple accept="image/*" required>
                                <div class="form-text">يمكنك اختيار أكثر من صورة. الصورة الأولى ستكون الصورة الرئيسية.</div>
                                @error('photos')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            سيتم مراجعة المعلومات من قبل الإدارة قبل النشر
                        </div>

                        <div class="text-end">
                            <a href="{{ route('front.cars') }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">إرسال البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
