@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-car me-2"></i> سياراتي
                </h4>
                <a href="{{ route('front.cars.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> إضافة سيارة جديدة
                </a>
            </div>
        </div>

        <div class="col-12">
            @if($cars->count() > 0)
                <div class="row g-4">
                    @foreach($cars as $car)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($car->photos->where('is_featured', true)->first())
                                    <img src="{{ asset($car->photos->where('is_featured', true)->first()->url) }}"
                                         class="card-img-top" alt="{{ $car->manufacturer }} {{ $car->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="fas fa-car fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $car->manufacturer }} {{ $car->model }}</h5>
                                        <span class="badge bg-{{ $car->status === 'found' ? 'success' : ($car->status === 'stolen' ? 'danger' : 'warning') }}">
                                            {{ __('car_status.' . $car->status) }}
                                        </span>
                                    </div>

                                    <p class="text-muted small mb-0">{{ $car->year ?? 'السنة غير محددة' }}</p>

                                    @if($car->location)
                                        <p class="card-text small mb-2">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            {{ $car->location }}
                                        </p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <span class="badge {{ $car->is_approved ? 'bg-success' : 'bg-warning' }}">
                                                {{ $car->is_approved ? 'معتمد' : 'في انتظار الموافقة' }}
                                            </span>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('user.cars.show', $car->id) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.cars.edit', $car->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $cars->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                    <h5>لا توجد سيارات مضافة</h5>
                    <p class="text-muted">قم بإضافة سيارة جديدة من خلال الضغط على زر "إضافة سيارة جديدة"</p>
                    <a href="{{ route('front.cars.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> إضافة سيارة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
