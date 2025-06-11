@extends('layouts.admin')
@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-car me-2"></i> تفاصيل السيارة: {{ $car->manufacturer }} {{ $car->model }}
            </h4>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-right"></i> العودة للقائمة
            </a>
        </div>

        <div class="card p-4 shadow-sm mb-4">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>رقم اللوحة:</strong> {{ $car->license_plate ?? 'غير معروف' }}</p>
                    <p><strong>الماركة:</strong> {{ $car->manufacturer }}</p>
                    <p><strong>الموديل:</strong> {{ $car->model }}</p>
                    <p><strong>سنة الصنع:</strong> {{ $car->year ?? 'غير معروف' }}</p>
                    <p><strong>اللون:</strong> {{ $car->color ?? 'غير معروف' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>الحالة:</strong> {{ __('car_status.' . $car->status) }}</p>
                    <p><strong>الموقع:</strong> {{ $car->location ?? 'غير معروف' }}</p>
                    <p><strong>تاريخ الفقدان:</strong> {{ $car->missing_date ? $car->missing_date->format('Y-m-d') : 'غير معروف' }}</p>
                    <p><strong>اسم المالك:</strong> {{ $car->owner_name ?? 'غير معروف' }}</p>
                    <p><strong>رقم التواصل:</strong> {{ $car->owner_contact ?? 'غير معروف' }}</p>
                </div>
            </div>

            @if($car->description)
                <div class="mt-3">
                    <h6 class="border-bottom pb-2">الوصف</h6>
                    <p class="text-muted">{{ $car->description }}</p>
                </div>
            @endif

            @if($car->notes)
                <div class="mt-3">
                    <h6 class="border-bottom pb-2">ملاحظات إضافية</h6>
                    <p class="text-muted">{{ $car->notes }}</p>
                </div>
            @endif
        </div>

        <h5 class="mt-4 mb-3"><i class="fas fa-images me-2"></i> صور السيارة</h5>
        <div class="row g-3">
            @forelse($car->photos as $photo)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset($photo->url) }}" class="card-img-top img-fluid rounded" alt="صورة للسيارة">
                        @if($photo->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-primary">الصورة الرئيسية</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-muted">لا توجد صور مرفوعة.</div>
            @endforelse
        </div>

        <hr class="my-5">

        <h5 class="mb-3"><i class="fas fa-eye me-2 text-warning"></i> بلاغات المشاهدة</h5>
        @if($car->reports->where('report_type', 'sighting')->count())
            <div class="list-group mb-4">
                @foreach($car->reports->where('report_type', 'sighting') as $report)
                    <div class="list-group-item">
                        <div class="fw-bold">{{ $report->location }}</div>
                        <small class="text-muted">{{ $report->created_at->translatedFormat('Y-m-d') }} - {{ $report->created_at->diffForHumans() }}</small>
                        <div>{{ $report->details }}</div>
                        @if($report->user)
                            <small class="text-primary">بواسطة {{ $report->user->name }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">لا توجد بلاغات مشاهدة حتى الآن.</p>
        @endif

        <h5 class="mb-3"><i class="fas fa-bug me-2 text-danger"></i> بلاغات الأخطاء</h5>
        @if($car->reports->where('report_type', 'error')->count())
            <div class="list-group">
                @foreach($car->reports->where('report_type', 'error') as $report)
                    <div class="list-group-item">
                        <div class="fw-bold">{{ $report->created_at->translatedFormat('Y-m-d') }} - {{ $report->created_at->diffForHumans() }}</div>
                        <div>{{ $report->details }}</div>
                        @if($report->user)
                            <small class="text-primary">بواسطة {{ $report->user->name }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">لا توجد بلاغات أخطاء حتى الآن.</p>
        @endif
    </div>
@endsection
