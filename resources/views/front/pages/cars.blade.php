@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row">
            {{-- Statistics Section --}}
            <div class="col-12 mb-4">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow bg-dark">
                            <div class="card-body">
                                <h6 class="mb-2 text-white">إجمالي السيارات</h6>
                                <h3 class="mb-0 text-white">{{ $allStatuses['total'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow bg-danger text-white">
                            <div class="card-body">
                                <h6 class="mb-2">السيارات المسروقة</h6>
                                <h3 class="mb-0">{{ $allStatuses['stolen'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow bg-warning">
                            <div class="card-body">
                                <h6 class="mb-2">السيارات المفقودة</h6>
                                <h3 class="mb-0">{{ $allStatuses['missing'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow bg-success text-white">
                            <div class="card-body">
                                <h6 class="mb-2">تم العثور عليها</h6>
                                <h3 class="mb-0">{{ $allStatuses['found'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Section --}}
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">بحث</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="رقم اللوحة، الماركة، الموديل..." value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الموقع</label>
                                <select name="location" class="form-select">
                                    <option value="">كل المواقع</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" @selected($location === request('location'))>
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الحالة</label>
                                <select name="status" class="form-select">
                                    <option value="">كل الحالات</option>
                                    <option value="missing" @selected(request('status') === 'missing')>مفقودة</option>
                                    <option value="found" @selected(request('status') === 'found')>تم العثور عليها</option>
                                    <option value="stolen" @selected(request('status') === 'stolen')>مسروقة</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> بحث
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Cars Grid --}}
            <div class="col-12">
                <div class="row g-4">
                    @forelse($cars as $car)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card h-100 border-0 shadow">
                                @if($car->photos->where('is_featured', true)->first())
                                    <img src="{{asset( 'storage/public/' . $car->photos->where('is_featured', true)->first()->path) }}"
                                         class="card-img-top" alt="{{ $car->manufacturer }} {{ $car->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="fas fa-car fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $car->manufacturer }} {{ $car->model }}</h5>
                                    <p class="text-muted small mb-2">{{ $car->year ?? 'السنة غير محددة' }}</p>

                                    <div class="mb-2">
                                        <span class="badge bg-{{ $car->status === 'found' ? 'success' : ($car->status === 'stolen' ? 'danger' : 'warning') }}">
                                            {{ __('car_status.' . $car->status) }}
                                        </span>
                                    </div>

                                    @if($car->location)
                                        <p class="card-text mb-1">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            {{ $car->location }}
                                        </p>
                                    @endif

                                    @if($car->missing_date)
                                        <p class="card-text small text-muted mb-3">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $car->missing_date->format('Y-m-d') }}
                                        </p>
                                    @endif

                                    <a href="{{ route('front.cars.show', $car->id) }}" class="btn btn-outline-primary w-100">
                                        عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                لا توجد سيارات مطابقة لمعايير البحث
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $cars->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
