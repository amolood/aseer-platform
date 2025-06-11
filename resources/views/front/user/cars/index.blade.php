@extends('layouts.user')

@section('user-content')
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-4"><i class="fas fa-car me-2 text-primary"></i> السيارات التي أضفتها</h5>
                <a href="{{ route('user.cars.create') }}" class="btn btn-success rounded-pill">
                    <i class="fas fa-plus me-1"></i> إضافة سيارة جديدة
                </a>
                <div class="table-responsive mt-3">
                    <table id="cars-table" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الماركة</th>
                                <th>الموديل</th>
                                <th>سنة الصنع</th>
                                <th>رقم اللوحة</th>
                                <th>حالة النشر</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>{{ $car->manufacturer }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->year }}</td>
                                    <td>{{ $car->license_plate }}</td>
                                    <td>
                                        {{ $car->is_approved ? '✔️ منشور' : '🚫 في إنتظار النشر' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('user.cars.show', $car->id) }}"
                                           class="btn btn-sm btn-outline-secondary mb-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.cars.edit', $car->id) }}"
                                           class="btn btn-sm btn-outline-primary mb-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $cars->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables JS (CDN or local version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#cars-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                }
            });
        });
    </script>
@endsection
