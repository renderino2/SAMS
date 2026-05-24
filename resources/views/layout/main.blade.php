@extends('../layout/base')

@section('body')
    <body class="main">
        @yield('content')
        
        <!-- Fixed Update Button for Settings Page -->
        @if(request()->routeIs('settings'))
        <button type="submit" form="settingsForm" class="btn btn-primary-soft btn-rounded btn-outline fixed bottom-0 right-0 mb-10 mr-10 z-50 shadow-lg">
            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
            Update Account
        </button>
        @endif

        <!-- Fixed Add Student Assistant Button for HR SA Account Management Page -->
        @if(request()->routeIs('sa.account.management'))
        <button type="button" id="toggleFormBtn" class="btn btn-primary-soft fixed bottom-0 right-0 mb-10 mr-10 z-50 shadow-lg">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Student Assistant
        </button>
        @endif

        <!-- Fixed Add Office Head Button for HR Office Head Management Page -->
        @if(request()->routeIs('officehead.management'))
        <button type="button" id="toggleFormBtn" class="btn btn-primary-soft fixed bottom-0 right-0 mb-10 mr-10 z-50 shadow-lg">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Office Head
        </button>
        @endif

        <!-- Fixed Add Office Button for HR Office Management Page -->
        @if(request()->routeIs('office.management'))
        <button type="button" id="toggleFormBtn" class="btn btn-primary-soft fixed bottom-0 right-0 mb-10 mr-10 z-50 shadow-lg">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Office
        </button>
        @endif

        <!-- Fixed Add Contract Button for HR Contract Management Page -->
        @if(request()->routeIs('contract.management'))
        <button type="button" id="toggleUploadPanel" class="btn btn-primary-soft fixed bottom-0 right-0 mb-10 mr-10 z-50 shadow-lg">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Contract
        </button>
        @endif

        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        @vite('resources/js/app.js')
        <!-- END: JS Assets-->

        @yield('script')
    </body>
@endsection
