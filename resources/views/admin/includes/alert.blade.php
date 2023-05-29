<!-- Alert success -->
@if (session('success'))
    <div class="row">
        <div class="alert alert-primary alert-dismissible fade show w-100" role="alert">
            <strong><i class="fas fa-thumbs-up"></i> </strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

<!-- Alert warning -->
@if (session('warning'))
    <div class="row">
        <div class="alert alert-warning alert-dismissible fade show w-100" role="alert">
            <strong><i class="fas fa-info-circle"></i> </strong> {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

<!-- Alert error -->
@if (session('error'))
    <div class="row">
        <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> </strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif


<!-- Alert search term -->
@if (request('term'))
    <div class="row">
        <div class="alert alert-primary w-100 alert-margin search-close w-100" role="alert">
            @lang('Search result for: ') {{ request('term') }}
            <a href="{{ request()->URL() }}" class="text-right float-right mt-1">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
@endif
