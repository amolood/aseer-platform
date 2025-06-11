@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            {{-- Car Details Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3 class="mb-1">{{ $car->manufacturer }} {{ $car->model }}</h3>
                            <p class="text-muted mb-0">{{ $car->year ?? 'السنة غير محددة' }}</p>
                        </div>
                        <span class="badge bg-{{ $car->status === 'found' ? 'success' : ($car->status === 'stolen' ? 'danger' : 'warning') }} ">
                            {{ __('car_status.' . $car->status) }}
                        </span>
                    </div>

                    {{-- Car Photos Carousel --}}
                    @if($car->photos->count() > 0)
                        <div id="carPhotosCarousel" class="custom-carousel mb-4 position-relative">
                            <div class="carousel-container" style="height: 400px; position: relative; overflow: hidden;">
                                @foreach($car->photos as $index => $photo)
                                    <div class="carousel-slide" style="position: absolute; width: 100%; height: 100%; opacity: {{ $index === 0 ? '1' : '0' }}; transition: opacity 0.5s ease-in-out;">
                                        <img src="{{ asset('storage/public/' . $photo->path) }}" class="d-block w-100 h-100" alt="صورة السيارة"
                                             style="object-fit: contain; background-color: #f8f9fa;">
                                    </div>
                                @endforeach
                            </div>
                            @if($car->photos->count() > 1)
                                <button class="carousel-nav-btn prev-btn" onclick="moveSlide(-1)" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(0,0,0,0.5); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="carousel-nav-btn next-btn" onclick="moveSlide(1)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(0,0,0,0.5); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif

                            {{-- Thumbnails --}}
                            <div class="carousel-thumbnails mt-2" style="display: flex; gap: 8px; overflow-x: auto; padding: 4px;">
                                @foreach($car->photos as $index => $photo)
                                    <div onclick="goToSlide({{ $index }})"
                                         style="width: 60px; height: 60px; flex-shrink: 0; cursor: pointer; border: 2px solid {{ $index === 0 ? '#007bff' : 'transparent' }}; border-radius: 4px; overflow: hidden;">
                                        <img src="{{ asset('storage/public/' . $photo->path) }}"
                                             alt="thumbnail"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <script>
                            let currentSlide = 0;
                            const slides = document.querySelectorAll('.carousel-slide');
                            const thumbnails = document.querySelectorAll('.carousel-thumbnails > div');
                            const totalSlides = slides.length;

                            function showSlide(n) {
                                currentSlide = (n + totalSlides) % totalSlides;

                                slides.forEach((slide, i) => {
                                    slide.style.opacity = i === currentSlide ? '1' : '0';
                                });

                                thumbnails.forEach((thumb, i) => {
                                    thumb.style.border = i === currentSlide ? '2px solid #007bff' : '2px solid transparent';
                                });
                            }

                            function moveSlide(direction) {
                                showSlide(currentSlide + direction);
                            }

                            function goToSlide(n) {
                                showSlide(n);
                            }
                        </script>
                    @else
                        <div class="text-center py-5 bg-light mb-4">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد صور متاحة</p>
                        </div>
                    @endif

                    {{-- Car Information --}}
                    <div class="row g-4">
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
                                @if($car->owner_contact && auth()->check())
                                    <li class="mb-2">
                                        <strong>رقم التواصل:</strong> {{ $car->owner_contact }}
                                    </li>
                                @endif
                            </ul>
                        </div>

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

            {{-- Report Forms --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sightingTab">
                                <i class="fas fa-eye me-1"></i> إبلاغ عن مشاهدة
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#errorTab">
                                <i class="fas fa-exclamation-triangle me-1"></i> الإبلاغ عن خطأ
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="reportTabsContent">
                        {{-- Sighting Report Form --}}
                        <div class="tab-pane fade show active" id="sightingTab">
                            <form action="{{ route('front.cars.report', $car->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="report_type" value="sighting">

                                <div class="mb-3">
                                    <label class="form-label">موقع المشاهدة</label>
                                    <input type="text" name="location" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تفاصيل المشاهدة</label>
                                    <textarea name="details" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">معلومات التواصل (اختياري)</label>
                                    <input type="text" name="contact_info" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">إرسال البلاغ</button>
                            </form>
                        </div>

                        {{-- Error Report Form --}}
                        <div class="tab-pane fade" id="errorTab">
                            <form action="{{ route('front.cars.report', $car->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="report_type" value="error">

                                <div class="mb-3">
                                    <label class="form-label">تفاصيل الخطأ</label>
                                    <textarea name="details" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">معلومات التواصل (اختياري)</label>
                                    <input type="text" name="contact_info" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-warning">إرسال البلاغ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Similar Cars --}}
            @if($relatedCars->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">سيارات مشابهة</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($relatedCars as $relatedCar)
                                <a href="{{ route('front.cars.show', $relatedCar->id) }}"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        @if($relatedCar->photos->where('is_featured', true)->first())
                                            <img src="{{ asset($relatedCar->photos->where('is_featured', true)->first()->url) }}"
                                                 class="rounded me-3" alt="صورة السيارة"
                                                 style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 64px; height: 64px;">
                                                <i class="fas fa-car text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $relatedCar->manufacturer }} {{ $relatedCar->model }}</h6>
                                            <small class="text-muted">{{ $relatedCar->location }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Share Buttons --}}
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">مشاركة</h5>
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
