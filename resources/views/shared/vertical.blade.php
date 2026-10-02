<!DOCTYPE html>

<html lang="ar" dir="rtl" @yield('html_attribute')>
<head>
    @include('shared.partials/title-meta')

    @yield('styles')
    @stack('styles')

    @include('shared.partials/head-css')
</head>
<body>
<div class="wrapper">

    @include('shared.partials/sidenav')

    <div class="page-content">
        
        @include('shared.partials/topbar')

        <main>

            @yield('content')

        </main>

        @include('shared.partials/footer')

    </div>

</div>

@include('shared.partials/footer-scripts')

{{-- Safe Global Error Boundary to prevent script cascade failure --}}
<script>
    window.addEventListener('error', function(e) {
        console.warn('Page-level isolated script warning:', e.message, e.filename, e.lineno);
    });
    window.addEventListener('unhandledrejection', function(e) {
        console.warn('Async unhandled promise rejection caught safely:', e.reason);
    });
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>
