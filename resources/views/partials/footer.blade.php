<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="h6 text-uppercase mb-3">{{ config('app.name', 'EventApp') }}</h5>
                <p class="small mb-0">{{ __('footer.description') }}</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="h6 text-uppercase mb-3">{{ __('footer.quick_links') }}</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-light text-decoration-none">{{ __('footer.home') }}</a></li>
                    <li class="mb-2"><a href="{{ route('search.index') }}" class="text-light text-decoration-none">{{ __('footer.find_events') }}</a></li>

                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="h6 text-uppercase mb-3">{{ __('footer.follow_us') }}</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light text-decoration-none">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-light text-decoration-none">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-light text-decoration-none">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-light text-decoration-none">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-3">
        <div class="text-center small">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'EventApp') }}. {{ __('footer.copyright') }}</p>
        </div>
    </div>
</footer>
