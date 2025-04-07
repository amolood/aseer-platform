@extends('layouts.app', ['page_title' => "تعديل بيانات أسير"])

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 text-center"><i class="fas fa-user-edit me-2"></i> تعديل بيانات أسير</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('front.detainees.update', $detainee->id) }}" enctype="multipart/form-data"
              class="bg-white p-4 p-md-5 rounded shadow">
            @csrf
            @method('PUT')

            {{-- القسم الأول: المعلومات الأساسية --}}
            <div class="mb-4">
                <h5 class="text-muted border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2"></i> المعلومات الشخصية
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم الكامل *</label>
                        <input type="text" name="name" class="form-control" value="{{ $detainee->name }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الجنس</label>
                        <select name="gender" class="form-control select2-select">
                            <option value="">-- اختر --</option>
                            <option value="male" {{ $detainee->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ $detainee->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ $detainee->birth_date }}">
                    </div>
                </div>
            </div>

            {{-- القسم الثاني: معلومات الاعتقال --}}
            <div class="mb-4">
                <h5 class="text-muted border-bottom pb-2 mb-3"><i class="fas fa-lock me-2"></i> معلومات الاعتقال</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">مكان الاعتقال</label>
                        <input type="text" name="location" class="form-control" value="{{ $detainee->location }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تاريخ الاعتقال</label>
                        <input type="date" name="detention_date" class="form-control" value="{{ $detainee->detention_date }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الحالة *</label>
                        <select name="status" class="form-control select2-select" required>
                            <option value="detained" {{ $detainee->status == 'detained' ? 'selected' : '' }}>معتقل</option>
                            <option value="missing" {{ $detainee->status == 'missing' ? 'selected' : '' }}>مفقود</option>
                            <option value="released" {{ $detainee->status == 'released' ? 'selected' : '' }}>مُفرج عنه</option>
                            <option value="martyr" {{ $detainee->status == 'martyr' ? 'selected' : '' }}>شهيد</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الجهة المعتقلة</label>
                        <input type="text" name="detaining_authority" class="form-control" value="{{ $detainee->detaining_authority }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اسم السجن</label>
                        <input type="text" name="prison_name" class="form-control" value="{{ $detainee->prison_name }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اختفاء قسري؟</label>
                        <select name="is_forced_disappearance" class="form-control select2-select">
                            <option value="0" {{ $detainee->is_forced_disappearance == 0 ? 'selected' : '' }}>لا</option>
                            <option value="1" {{ $detainee->is_forced_disappearance == 1 ? 'selected' : '' }}>نعم</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- القسم الثالث: جهة الاتصال والملاحظات --}}
            <div class="mb-4">
                <h5 class="text-muted border-bottom pb-2 mb-3"><i class="fas fa-user-friends me-2"></i> جهة الاتصال</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">إسم صاحب البلاغ</label>
                        <input type="text" name="family_contact_name" class="form-control" value="{{ $detainee->family_contact_name }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم هاتف صاحب البلاغ</label>
                        <input type="text" name="family_contact_phone" class="form-control" value="{{ $detainee->family_contact_phone }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">صلة القرابة</label>
                        <input type="text" name="source" class="form-control" value="{{ $detainee->source }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">ملاحظات إضافية</label>
                        <textarea name="notes" class="form-control" rows="4">{{ $detainee->notes }}</textarea>
                    </div>
                </div>
            </div>

            {{-- القسم الرابع: الصور --}}
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3" style="color: red;">
                    <i class="fas fa-image me-2"></i> صور للأسير
                </h5>
                <div class="col-md-12">
                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">يمكن رفع عدة صور دفعة واحدة</small>
                </div>

                @if($detainee->photos->count() > 0)
                    <div class="mt-3">
                        <h6>الصور الحالية:</h6>
                        <div class="row">
                            @foreach($photos as $photo)
                                <div class="col-md-3 mb-3 photo-container" data-photo-id="{{ $photo->id }}">
                                    <div class="position-relative">
                                        <img src="{{ $photo->url }}" class="img-fluid rounded" alt="صورة الأسير">
                                        <button type="button" class="btn btn-outline-danger btn-sm position-absolute top-0 end-0 m-2 delete-photo" data-photo-id="{{ $photo->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <input type="hidden" name="deleted_photos" id="deleted_photos" value="">
            </div>

            {{-- زر الإرسال --}}
            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-1"></i> حفظ التعديلات
                </button>
            </div>

        </form>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        console.log('Script loaded'); // Debug log

        let deletedPhotos = [];

        // Add click event listeners to all delete buttons
        $('.delete-photo').on('click', function() {
            console.log('Delete button clicked'); // Debug log
            const photoId = $(this).data('photo-id');
            deletePhoto(photoId);
        });

        function deletePhoto(photoId) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                console.log('Deleting photo:', photoId); // Debug log
                // Add to deleted photos array
                deletedPhotos.push(photoId);
                // Update hidden input
                $('#deleted_photos').val(deletedPhotos.join(','));
                // Remove the photo container
                $(`.photo-container[data-photo-id="${photoId}"]`).remove();
            }
        }
    });
</script>
@endsection
