@extends('layouts.app', ['page_title' => "الأسرى الذين قمت بإضافتهم"])

@section('content')

    <div class="container py-5 pt-4">

        {{-- Page Header --}}
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-md-6">
                <h2 class="mb-0 text-dark"><i class="fas fa-users me-2 text-primary"></i> الأسرى الذين قمت بإضافتهم</h2>
            </div>
            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <a href="{{ route('front.detainees.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> إضافة جديد
                </a>
            </div>
        </div>

        {{-- Search Results --}}
        @if($detainees->count())
            <div class="row g-4">
                @foreach($detainees as $detainee)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0">
                            @php
                                $image = $detainee->photos->first()->path ?? 'images/default-avatar.png';
                                if(str_contains($image,'images/default-avatar.png')){
                                    $image = 'images/default-avatar.png';
                                    }else{
                                    $image = 'storage/public/' . $image;
                                    }
                            @endphp
                            {{-- Image --}}
                            <a href="{{ route('front.detainees.show', $detainee->id) }}" target="_blank">
                                <img src="{{ asset($image) }}" class="card-img-top"
                                     style="height: 220px; object-fit: cover;" alt="صورة الأسير">
                            </a>

                            {{-- Card Body --}}

                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ collect(explode(' ', $detainee->name))->take(3)->implode(' ') }}</h5>
                                <p class="text-muted mb-1">
                                    <strong>الحالة : </strong> {{ __('status.' . $detainee->status) }}</p>
                                <p class="text-muted"><strong>بتاريخ : </strong> {{ $detainee->detention_date }}
                                </p>

                                <a href="{{ route('front.detainees.show', $detainee->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $detainees->withQueryString()->links() }}
            </div>
        @else
            <div class="alert alert-info mt-4">لا توجد نتائج حاليًا.</div>
        @endif
    </div>
@endsection
