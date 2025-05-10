@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $car->manufacturer }} {{ $car->model }}</h4>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-{{ $car->status === 'found' ? 'success' : ($car->status === 'stolen' ? 'danger' : 'warning') }}">
                            {{ __('car_status.' . $car->status) }}
                        </span>
                        <span class="badge {{ $car->is_approved ? 'bg-success' : 'bg-warning' }}">
                            {{ $car->is_approved ? 'معتمد' : 'في انتظار الموافقة' }}
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('user.cars.edit', $car->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> تعديل
                    </a>
                    <a href="{{ route('user.cars.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i> العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Car Photos --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @if($car->photos->count() > 0)
                        <div id="carPhotosCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($car->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($photo->url) }}" class="d-block w-100" alt="صورة السيارة"
                                             style="height: 400px; object-fit: contain;">
                                        @if($photo->is_featured)
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-primary">الصورة الرئيسية</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($car->photos->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carPhotosCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">السابق</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carPhotosCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">التالي</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد صور متاحة</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Car Details --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-4">
                        {{-- Basic Information --}}
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">معلومات السيارة</h5>
                            <ul class="list-unstyled">
                                @if($car->license_plate)
                                    <li class="mb-2">
                                        <strong>رقم اللوحة:</strong> {{ $car->license_plate }}
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <strong>الماركة:</strong> {{ $car->manufacturer }}
                                </li>
                                <li class="mb-2">
                                    <strong>الموديل:</strong> {{ $car->model }}
                                </li>
                                @if($car->year)
                                    <li class="mb-2">
                                        <strong>سنة الصنع:</strong> {{ $car->year }}
                                    </li>
                                @endif
                                @if($car->color)
                                    <li class="mb-2">
                                        <strong>اللون:</strong> {{ $car->color }}
                                    </li>
                                @endif
                            </ul>
                        </div>

                        {{-- Missing Information --}}
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">معلومات الفقدان</h5>
                            <ul class="list-unstyled">
                                @if($car->location)
                                    <li class="mb-2">
                                        <strong>الموقع:</strong> {{ $car->location }}
                                    </li>
                                @endif
                                @if($car->missing_date)
                                    <li class="mb-2">
                                        <strong>تاريخ الفقدان:</strong> {{ $car->missing_date->format('Y-m-d') }}
                                    </li>
                                @endif
                                @if($car->owner_name)
                                    <li class="mb-2">
                                        <strong>اسم المالك:</strong> {{ $car->owner_name }}
                                    </li>
                                @endif
                                @if($car->owner_contact)
                                    <li class="mb-2">
                                        <strong>رقم التواصل:</strong> {{ $car->owner_contact }}
                                    </li>
                                @endif
                            </ul>
                        </div>

                        {{-- Description & Notes --}}
                        @if($car->description)
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">الوصف</h5>
                                <p class="text-muted">{{ $car->description }}</p>
                            </div>
                        @endif

                        @if($car->notes)
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">ملاحظات إضافية</h5>
                                <p class="text-muted">{{ $car->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Reports Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">البلاغات</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>بلاغات المشاهدة:</span>
                        <span class="badge bg-primary rounded-pill">{{ $car->reports->where('report_type', 'sighting')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>بلاغات الأخطاء:</span>
                        <span class="badge bg-danger rounded-pill">{{ $car->reports->where('report_type', 'error')->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Share Widget --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">مشاركة</h5>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/?text={{ urlencode(route('front.cars.show', $car->id)) }}"
                           class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i> مشاركة عبر واتساب
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('front.cars.show', $car->id)) }}"
                           class="btn btn-info text-white" target="_blank">
                            <i class="fab fa-twitter me-2"></i> مشاركة عبر تويتر
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('front.cars.show', $car->id)) }}"
                           class="btn btn-primary" target="_blank">
                            <i class="fab fa-facebook me-2"></i> مشاركة عبر فيسبوك
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
