@extends('layouts.user')

@section('user-content')
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5><i class="fas fa-car me-2 text-primary"></i> تفاصيل السيارة</h5>
                    <a href="{{ route('user.cars.edit', $car->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> تعديل البيانات
                    </a>
                </div>

                @if($car->photos->count() > 0)
                    <div class="row mb-4">
                        @foreach($car->photos as $photo)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/public/' . $photo->path) }}"
                                     class="img-fluid rounded {{ $photo->is_featured ? 'border border-primary' : '' }}"
                                     alt="صورة السيارة">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th style="width: 150px;">الماركة:</th>
                                <td>{{ $car->manufacturer }}</td>
                            </tr>
                            <tr>
                                <th>الموديل:</th>
                                <td>{{ $car->model }}</td>
                            </tr>
                            <tr>
                                <th>سنة الصنع:</th>
                                <td>{{ $car->year }}</td>
                            </tr>
                            <tr>
                                <th>رقم اللوحة:</th>
                                <td>{{ $car->license_plate }}</td>
                            </tr>
                            <tr>
                                <th>اللون:</th>
                                <td>{{ $car->color }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th style="width: 150px;">الموقع:</th>
                                <td>{{ $car->location }}</td>
                            </tr>
                            <tr>
                                <th>الحالة:</th>
                                <td>
                                    @switch($car->status)
                                        @case('missing')
                                            <span class="badge bg-danger">مفقودة</span>
                                            @break
                                        @case('found')
                                            <span class="badge bg-success">تم العثور عليها</span>
                                            @break
                                        @case('stolen')
                                            <span class="badge bg-danger">مسروقة</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>تاريخ الفقدان:</th>
                                <td>{{ $car->missing_date ? \Carbon\Carbon::parse($car->missing_date)->format('Y-m-d') : '—' }}</td>
                            </tr>
                            <tr>
                                <th>حالة النشر:</th>
                                <td>{{ $car->is_approved ? '✔️ منشور' : '🚫 في إنتظار النشر' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($car->description)
                    <div class="mt-4">
                        <h6 class="text-primary">الوصف:</h6>
                        <p class="mb-0">{{ $car->description }}</p>
                    </div>
                @endif

                @if($car->notes)
                    <div class="mt-4">
                        <h6 class="text-primary">ملاحظات إضافية:</h6>
                        <p class="mb-0">{{ $car->notes }}</p>
                    </div>
                @endif

                @if($car->owner_name || $car->owner_contact)
                    <div class="mt-4">
                        <h6 class="text-primary">معلومات المالك:</h6>
                        @if($car->owner_name)
                            <p class="mb-1"><strong>الاسم:</strong> {{ $car->owner_name }}</p>
                        @endif
                        @if($car->owner_contact)
                            <p class="mb-0"><strong>معلومات الاتصال:</strong> {{ $car->owner_contact }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
