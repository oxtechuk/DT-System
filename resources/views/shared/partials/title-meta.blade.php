<meta charset="utf-8"/>
<title>{{ $title }} | DT-System — Workspace Management</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="DT-System — Workspace Management System" name="description"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<!-- App favicon -->
<link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon"/>
<!-- Apply saved primary color before paint (prevents FOUC) -->
<script>
(function() {
 var c = localStorage.getItem('dt_primary_color') || '{{ $appPrimaryColor ?? "" }}';
 if (c && /^#[0-9A-Fa-f]{6}$/.test(c)) {
 document.documentElement.style.setProperty('--color-primary', c);
 }
})();
</script>