@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 main-box">
            <div class="col-12 px-0">
                <div class="col-12 p-0 row">
                    <div class="col-12 col-lg-4 py-3 px-3">
                        <span class="fas fa-car"></span> إدارة السيارات
                    </div>
                    <div class="col-12 col-lg-4 p-2">
                    </div>
                </div>
                <div class="col-12 divider" style="min-height: 2px;"></div>
            </div>

            <div class="col-12 p-3">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">بحث</label>
                        <input type="text" name="q" class="form-control" placeholder="رقم اللوحة، الموديل..." value="{{ request('q') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الموقع</label>
                        <select name="location" class="form-control select2-select">
                            <option value="">الكل</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" @selected($location == request('location'))>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-control select2-select">
                            <option value="">الكل</option>
                            <option value="missing" @selected(request('status') === 'missing')>مفقودة</option>
                            <option value="found" @selected(request('status') === 'found')>تم العثور عليها</option>
                            <option value="stolen" @selected(request('status') === 'stolen')>مسروقة</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3 mt-4">
                        <button class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i> تنفيذ البحث
                        </button>
                        <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times-circle me-1"></i> مسح البحث
                        </a>
                    </div>
                </form>
            </div>

            <div class="col-12 p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم اللوحة</th>
                                <th>الماركة والموديل</th>
                                <th>الحالة</th>
                                <th>تاريخ الفقدان</th>
                                <th>الموافقة</th>
                                <th>بواسطة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>{{ $car->license_plate ?? '—' }}</td>
                                    <td>{{ $car->manufacturer }} {{ $car->model }} {{ $car->year }}</td>
                                    <td>{{ __('car_status.' . $car->status) }}</td>
                                    <td>{{ $car->missing_date ? $car->missing_date->format('Y-m-d') : '—' }}</td>
                                    <td>
                                        @if($car->is_approved)
                                            @can('cars-update')
                                                <form method="POST" action="{{ route('admin.cars.unapprove', $car->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times-circle me-1"></i> إلغاء
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">معتمد</span>
                                            @endcan
                                        @else
                                            @can('cars-update')
                                                <form method="POST" action="{{ route('admin.cars.approve', $car->id) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-warning">
                                                        <i class="fas fa-check"></i> موافقة
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-warning">في انتظار الموافقة</span>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>
                                        @if($car->user)
                                            <a href="{{ route('admin.users.show', $car->user->id) }}" class="text-decoration-underline">
                                                {{ $car->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير معروف</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('cars-read')
                                            <a href="{{ route('admin.cars.show', $car->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('cars-update')
                                            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('cars-delete')
                                            <form method="POST" action="{{ route('admin.cars.destroy', $car->id) }}" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد نتائج</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="col-12 p-3">
                    {{ $cars->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
