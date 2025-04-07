@extends('layouts.app', ['page_title' => "تفاصيل الأسير"])

@section('content')
    <div class="container py-5">

        {{-- Back & Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <a href="{{ route('front.detainees') }}" class="btn btn-outline-secondary rounded-pill px-4 mb-2">
                <i class="fas fa-arrow-right me-2"></i> عودة إلى القائمة
            </a>

            <div class="d-flex flex-wrap gap-2 mb-2">
                {{-- Share on social media --}}
                <div class="dropdown">
                    <button class="btn btn-outline-primary rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-share-alt me-2"></i>   شارك
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end text-end">
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="https://www.facebook/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}">
                                <i class="fab fa-facebook me-2 text-primary"></i> فيسبوك
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text=قصة أسير">
                                <i class="fab fa-twitter me-2 text-info"></i>  تويتر
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}">
                                <i class="fab fa-whatsapp me-2 text-success"></i> واتساب
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="https://t.me/share/url?url={{ urlencode(request()->fullUrl()) }}&text=قصة أسير">
                                <i class="fab fa-telegram me-2 text-primary"></i> تيليجرام
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); toastr.success('تم نسخ الرابط')">
                                <i class="fas fa-copy me-2 text-muted"></i> نسخ الرابط
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Edit button - only show if user is authorized to edit --}}
                @if(auth()->check() && $detainee->user_id === auth()->id())
                    <a href="{{ route('front.detainees.edit', $detainee->id) }}" class="btn btn-outline-warning rounded-pill px-4">
                        <i class="fas fa-edit me-2"></i> تعديل البيانات
                    </a>
                @endif

                {{-- Other buttons --}}
{{--                @if($detainee->status !== 'martyr')--}}
{{--                    <button class="btn btn-outline-warning rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#seenModal">--}}
{{--                        <i class="fas fa-eye me-2"></i> رأيت هذا الشخص --}}
{{--                    </button>--}}
{{--                @endif--}}
{{--                <button class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#reportModal">--}}
{{--                    <i class="fas fa-flag me-2"></i> تبليغ عن خطأ--}}
{{--                </button>--}}
            </div>
        </div>

        {{-- Featured Photo --}}
        @php $featured = $detainee->photos()->featured()->first(); @endphp
        @if($featured)
            <div class="mb-4 text-center">
                <a href="{{ $featured->url }}" data-fancybox="detainee-photo" data-caption="{{ $detainee->name }}">
                    <img src="{{ $featured->url }}" class="img-fluid rounded shadow"
                         style="max-height: 400px; object-fit: contain; cursor: zoom-in;" alt="صورة الأسير المميزة">
                </a>

            </div>
        @endif

        {{-- Profile Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="row g-0">
                {{-- Info --}}
                <div class="col-md-12 p-5">
                    <h2 class="text-center mb-4 fw-bold">{{ $detainee->name }}</h2>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                            @if($detainee->gender)
                                <tr>
                                    <th scope="row"><i class="fas fa-venus-mars me-2"></i> الجنس</th>
                                    <td>{{ $detainee->gender === 'male' ? 'ذكر' : 'أنثى' }}</td>
                                </tr>
                            @endif
                            @if($detainee->birth_date && \Carbon\Carbon::parse($detainee->birth_date)->isValid())
                                <tr>
                                    <th scope="row"><i class="fas fa-birthday-cake me-2"></i> تاريخ الميلاد</th>
                                    <td>{{ $detainee->birth_date }} <strong>العمر : </strong> <span> {{ \Carbon\Carbon::parse($detainee->birth_date)->age }}</span></td>
                                </tr>
                            @endif
                            @if($detainee->detention_date)
                                @php
                                    $detentionDate = \Carbon\Carbon::parse($detainee->detention_date);
                                    $threeYearsAgo = \Carbon\Carbon::now()->subYears(3);
                                @endphp

                                @if($detentionDate->isAfter($threeYearsAgo))
                                    <tr>
                                        <th scope="row"><i class="fas fa-calendar-alt me-2"></i> تاريخ الاعتقال</th>
                                        <td>{{ $detainee->detention_date }}</td>
                                    </tr>
                                @endif
                            @endif
                            @if($detainee->location)
                                <tr>
                                    <th scope="row"><i class="fas fa-map-marker-alt me-2"></i> مكان الاعتقال</th>
                                    <td>{{ $detainee->location }}</td>
                                </tr>
                            @endif
                            @if($detainee->status)
                                <tr>
                                    <th scope="row"><i class="fas fa-flag me-2"></i> الحالة</th>
                                    <td><span class="badge bg-{{ $detainee->status === 'martyr' ? 'danger' : ($detainee->status === 'released' ? 'success' : 'danger') }}">{{ __('status.' . $detainee->status) }}</span></td>
                                </tr>
                            @endif
                            @if($detainee->detaining_authority)
                                <tr>
                                    <th scope="row"><i class="fas fa-shield-alt me-2"></i> الجهة المعتقلة</th>
                                    <td>{{ $detainee->detaining_authority }}</td>
                                </tr>
                            @endif
                            @if($detainee->prison_name)
                                <tr>
                                    <th scope="row"><i class="fas fa-university me-2"></i> اسم السجن</th>
                                    <td>{{ $detainee->prison_name }}</td>
                                </tr>
                            @endif
                            @if($detainee->is_forced_disappearance !== null)
                                <tr>
                                    <th scope="row"><i class="fas fa-user-secret me-2"></i> اختفاء قسري</th>
                                    <td>{{ $detainee->is_forced_disappearance ? 'نعم' : 'لا' }}</td>
                                </tr>
                            @endif
{{--                            @if($detainee->family_contact_name && $detainee->status !== 'martyr')--}}
{{--                                <tr>--}}
{{--                                    <th scope="row"><i class="fas fa-user-friends me-2"></i> جهة الاتصال</th>--}}
{{--                                    <td>{{ $detainee->family_contact_name }}</td>--}}
{{--                                    <td>{{ $detainee->family_contact_name }}</td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                            @if($detainee->family_contact_phone && $detainee->status !== 'martyr')--}}
{{--                                <tr>--}}
{{--                                    <th scope="row"><i class="fas fa-phone-alt me-2"></i> رقم جهة الاتصال</th>--}}
{{--                                    <td>{{ $detainee->family_contact_phone }}</td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
                            @if($detainee->source)
                                <tr>
                                    <th scope="row"><i class="fas fa-link me-2"></i> المصدر</th>
                                    <td>{{ $detainee->source }}</td>
                                </tr>
                            @endif
                            @if($detainee->status === 'martyr' && $detainee->martyr_date != null || $detainee->martyr_place != null || $detainee->martyr_reason != null || $detainee->martyr_notes != null)
                                <tr>
                                    <td colspan="2">
                                        <div class="alert alert-danger">
                                            <h5 class="mb-3"><i class="fas fa-heart-broken me-2"></i> معلومات الاستشهاد</h5>
                                            <ul class="list-unstyled mb-0">
                                                @if($detainee->martyr_date)
                                                    <li class="mb-1"><strong>تاريخ الوفاة:</strong> {{ $detainee->martyr_date }}</li>
                                                @endif
                                                @if($detainee->martyr_place)
                                                    <li class="mb-1"><strong>مكان الاستشهاد:</strong> {{ $detainee->martyr_place }}</li>
                                                @endif
                                                @if($detainee->martyr_reason)
                                                    <li class="mb-1"><strong>سبب الوفاة:</strong> {{ $detainee->martyr_reason }}</li>
                                                @endif
                                                @if($detainee->martyr_notes)
                                                    <li class="mb-1"><strong>ملاحظات إضافية:</strong> {{ $detainee->martyr_notes }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($detainee->detention_notes)
                                <tr>
                                    <td colspan="2">
                                        <div class="p-4 bg-light rounded-3">
                                            <i class="fas fa-sticky-note text-info me-2"></i>
                                            <strong>ملاحظات الاعتقال:</strong> {{ $detainee->detention_notes }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                            <livewire:detainee-follow-button :detainee="$detainee" />

                        </table>

                        {{-- Photo Gallery --}}

                        @if($photos->count() > 1)
                            <section class="bg-light py-5">
                                <div class="container">
                                    <div class="text-center mb-5">
                                        <h4 class="text-muted">معرض الصور</h4>
                                        <h5 class="fw-bold">صور أخرى للأسير</h5>
                                    </div>

                                    <div class="row g-4">
                                        @foreach($photos as $photo)
                                            @if($photo->is_featured)
                                                @continue
                                            @endif
                                            <div class="col-sm-6 col-md-4 col-lg-3">
                                                <div class="card shadow-md border-0 rounded-3 overflow-hidden">
                                                    <a href="{{ $photo->url }}" data-fancybox="gallery" data-caption="صورة للأسير">
                                                        <img src="{{ $photo->url }}" class="img-fluid" style="height: 200px; object-fit: cover;" alt="صورة للأسير">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        {{-- Call Button --}}
{{--        @if($detainee->family_contact_phone && $detainee->status !== 'martyr')--}}
{{--            <div class="text-center mb-5">--}}
{{--                <a href="tel:{{ $detainee->family_contact_phone }}" class="btn btn-lg btn-success rounded-pill px-5 py-3 shadow-lg">--}}
{{--                    <i class="fal fa-phone fa-lg me-2"></i> اتصل بالعائلة--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        @endif--}}

        {{-- Related Detainees --}}
        @if($relatedDetainees->count())
            <section class="mt-5">
                <div class="text-center mb-5">
                    <h2 class="fs-5 text-muted">قد يهمك أيضًا</h2>
                    <h3 class="fw-bold">أسرى مشابهين</h3>
                </div>
                <div class="row g-4">
                    @foreach($relatedDetainees as $rel)
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <div class="card h-100 shadow-md border-0 rounded-3">
                                @php $image = $rel->photos()->featured()->first()->url ?? ($rel->photos()->first()->url ?? asset('images/default-avatar.png')); @endphp
                                <img src="{{ $image }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="صورة الأسير">
                                <div class="card-body">
                                    <h5 class="card-title fw-semibold">{{ $rel->name }}</h5>
                                    <p class="card-text mb-2">
                                        <strong>الحالة:</strong> {{ __('status.' . $rel->status) }}
                                    </p>
                                    <a href="{{ route('front.detainees.show', $rel->id) }}" class="btn btn-sm btn-outline-primary rounded-pill w-100">عرض التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>



@endsection
