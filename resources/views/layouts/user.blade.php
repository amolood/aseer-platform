@extends('layouts.app')
@section('content')
<div class="col-12" style="min-height:100vh;background:#f4f4f4">
    <div class="col-12 p-0" style="background:#fff">
        <div class="container">
            <div class="col-12 p-0 d-flex align-items-center justify-content-center" style="min-height:10vh;">

            </div>
        </div>


        <div class="col-12 p-0 border-lg-top">
            <div class="container p-0">
                <div class="col-12 row user-menu">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid p-0">
                            <div class="col-12 px-0 row d-flex m-0 py-3 py-lg-0 justify-content-between align-items-center d-flex d-lg-none">

                            <div class="navbar-brand navbar-toggler  font-2 px-3 col-auto" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="color: inherit;">لوحة التحكم</div>
                            <button class="navbar-toggler d-flex col-auto" style="box-shadow:none;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fal fa-bars"></span>
                            </button>
                            </div>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div class="navbar-nav ms-auto mb-0 mb-lg-0">
                                    <a href="{{route('user.dashboard')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-home mx-2"></span> الرئيسية
                                    </a>
                                    <a href="{{route('user.detainees.index')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-home mx-2"></span> الأسرى الذين أضفتهم
                                    </a>
                                    <a href="{{route('user.cars.index')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-car mx-2"></span> السيارات
                                    </a>
                                    <a href="{{route('user.support')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-comments-alt mx-2"></span> الدعم
                                    </a>
                                    <a href="{{route('user.notifications')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-bells mx-2"></span> التنبيهات
                                    </a>
                                    <a href="{{route('user.profile.edit')}}" class="user-menu d-flex align-items-center col-auto justify-content-lg-center justify-content-start py-3 px-2" style="min-width:120px;border-bottom:6px solid transparent;height: 100%;color: inherit;transition: 0s all ease;">
                                        <span class="fal fa-wrench mx-2"></span> الاعدادات
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

    </div>
    <div class="col-12">
        <div class="container py-2 px-2">
            <div class="col-12 p-0 row d-flex ">
                @yield('user-content')
            </div>
        </div>
    </div>
</div>
{{--
<x-start />
<x-numbers />
<x-faqs />
<x-blog />
<x-about />
<x-call-to-action /> --}}
@endsection
@section('scripts')
<script type="module">
    setTimeout(function(){
        $('a[href="'+ window.location.href + '"], a[href="'+ window.location.path + '"]').addClass('active');
    },10);
</script>
@endsection
