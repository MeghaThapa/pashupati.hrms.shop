@php
    use \App\Helpers\AppHelper;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{{ __('Productify is a production management system build to simplify production or manufacturing process. Productify is lightweight, secure and fast and based on laravel.') }}">
    <meta name="keywords" content="{{ __('Productify, Production management system, Manufacturing system, Inventory system, Stock management, Workshop management, Row material management, Garments System, Food and Beverage, Furniture Companies') }}">
    <meta name="author" content="{{ __('Codeshaper') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ AppHelper::instance()->getGeneralSettigns()->compnayTagline }}</title>
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ AppHelper::instance()->getGeneralSettigns()->favicon }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ AppHelper::instance()->getGeneralSettigns()->favicon }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ AppHelper::instance()->getGeneralSettigns()->favicon }}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Sen:400,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href=" {{ asset('css/landing.css') }}">
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
</head>
<body class="is-boxed has-animations">
    <div class="body-wrap boxed-container">
        <main>
            <section class="hero text-center">
                <div class="container-sm">
                    <div class="hero-inner main-logo">
                        <img src="{{ asset('img/logo-black.svg') }}" alt="{{ __('Productify::Production Management System') }}">
                        <p class="hero-paragraph is-revealing">{{ __('Productify is a production management system build to simplify production or manufacturing process. Productify is lightweight, secure and fast and based on laravel.') }}</p>
                        <div class="hero-form newsletter-form field field-grouped is-revealing">
                            <div class="control demo-btn">
                                <a class="button button-primary button-block button-shadow" href="https://codecanyon.net/item/productifyproduction-management-system/26526177" target="__blank">Buy Now</a>
                                <a class="button button-primary button-block button-shadow mr-3" href="https://productify.codeshaper.net/login" target="__blank">Check Demo</a>
                            </div>
                        </div>
                        <div class="hero-browser">
                            <div class="bubble-3 is-revealing">
                                <svg width="427" height="286" viewBox="0 0 427 286" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M213.5 286C331.413 286 427 190.413 427 72.5S304.221 16.45 186.309 16.45C68.396 16.45 0-45.414 0 72.5S95.587 286 213.5 286z" id="bubble-3-a"/>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <mask id="bubble-3-b" fill="#fff">
                                            <use xlink:href="#bubble-3-a"/>
                                        </mask>
                                        <use fill="#4E8FF8" xlink:href="#bubble-3-a"/>
                                        <path d="M64.5 129.77c117.913 0 213.5-95.588 213.5-213.5 0-117.914-122.779-56.052-240.691-56.052C-80.604-139.782-149-201.644-149-83.73c0 117.913 95.587 213.5 213.5 213.5z" fill="#1274ED" mask="url(#bubble-3-b)"/>
                                        <path d="M381.5 501.77c117.913 0 213.5-95.588 213.5-213.5 0-117.914-122.779-56.052-240.691-56.052C236.396 232.218 168 170.356 168 288.27c0 117.913 95.587 213.5 213.5 213.5z" fill="#75ABF3" mask="url(#bubble-3-b)"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="bubble-4 is-revealing">
                                <svg width="230" height="235" viewBox="0 0 230 235" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M196.605 234.11C256.252 234.11 216 167.646 216 108 216 48.353 167.647 0 108 0S0 48.353 0 108s136.959 126.11 196.605 126.11z" id="bubble-4-a"/>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <mask id="bubble-4-b" fill="#fff">
                                            <use xlink:href="#bubble-4-a"/>
                                        </mask>
                                        <use fill="#7CE8DD" xlink:href="#bubble-4-a"/>
                                        <circle fill="#3BDDCC" mask="url(#bubble-4-b)" cx="30" cy="108" r="108"/>
                                        <circle fill="#B1F1EA" opacity=".7" mask="url(#bubble-4-b)" cx="265" cy="88" r="108"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="hero-browser-inner is-revealing">
                                <img src="{{ asset('img/laptop.png') }}">
                            </div>
                            <div class="bubble-1 is-revealing">
                                <svg width="61" height="52" viewBox="0 0 61 52" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M32 43.992c17.673 0 28.05 17.673 28.05 0S49.674 0 32 0C14.327 0 0 14.327 0 32c0 17.673 14.327 11.992 32 11.992z" id="bubble-1-a"/>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <mask id="bubble-1-b" fill="#fff">
                                            <use xlink:href="#bubble-1-a"/>
                                        </mask>
                                        <use fill="#FF6D8B" xlink:href="#bubble-1-a"/>
                                        <path d="M2 43.992c17.673 0 28.05 17.673 28.05 0S19.674 0 2 0c-17.673 0-32 14.327-32 32 0 17.673 14.327 11.992 32 11.992z" fill="#FF4F73" mask="url(#bubble-1-b)"/>
                                        <path d="M74 30.992c17.673 0 28.05 17.673 28.05 0S91.674-13 74-13C56.327-13 42 1.327 42 19c0 17.673 14.327 11.992 32 11.992z" fill-opacity=".8" fill="#FFA3B5" mask="url(#bubble-1-b)"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="bubble-2 is-revealing">
                                <svg width="179" height="126" viewBox="0 0 179 126" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M104.697 125.661c41.034 0 74.298-33.264 74.298-74.298s-43.231-7.425-84.265-7.425S0-28.44 0 12.593c0 41.034 63.663 113.068 104.697 113.068z" id="bubble-2-a"/>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <mask id="bubble-2-b" fill="#fff">
                                            <use xlink:href="#bubble-2-a"/>
                                        </mask>
                                        <use fill="#838DEA" xlink:href="#bubble-2-a"/>
                                        <path d="M202.697 211.661c41.034 0 74.298-33.264 74.298-74.298s-43.231-7.425-84.265-7.425S98 57.56 98 98.593c0 41.034 63.663 113.068 104.697 113.068z" fill="#626CD5" mask="url(#bubble-2-b)"/>
                                        <path d="M43.697 56.661c41.034 0 74.298-33.264 74.298-74.298s-43.231-7.425-84.265-7.425S-61-97.44-61-56.407C-61-15.373 2.663 56.661 43.697 56.661z" fill="#B1B6F1" opacity=".64" mask="url(#bubble-2-b)"/>
                                    </g>
                                </svg>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="features section text-center">
                <div class="container">
                    <div class="features-inner section-inner has-bottom-divider">
                        <div class="features-wrap">
                            <div class="feature is-revealing">
                                <div class="feature-inner">
                                    <div class="feature-icon">
                                        <img src="{{ asset('img/shopping.png') }}" alt="PURCHASE">
                                    </div>
                                    <h3 class="feature-title">PURCHASE</h3>
                                    <p class="text-sm">Create purchases for your company. Dynamic product fields, auto cost calculations, and purchase printable purchase invoice generation.</p>
                                </div>
                            </div>
                            <div class="feature is-revealing">
                                <div class="feature-inner">
                                    <div class="feature-icon">
                                        <img src="{{ asset('img/processing.png') }}" alt="PROCESSING">
                                    </div>
                                    <h3 class="feature-title">PROCESSING</h3>
                                    <p class="text-sm">Create a processing product for purchase. Dynamic processing steps and dynamic staff section for each processing step.</p>
                                </div>
                            </div>
                        </div>
                        <div class="features-wrap">
                            <div class="feature is-revealing">
                                <div class="feature-inner">
                                    <div class="feature-icon">
                                        <img src="{{ asset('img/finished.png') }}" alt="FINISHED">
                                    </div>
                                    <h3 class="feature-title">FINISHED</h3>
                                    <p class="text-sm">Create a finished product for purchase. Dynamic product sizes for each purchase. And the finished product can be used for transfer.</p>
                                </div>
                            </div>
                            <div class="feature is-revealing">
                                <div class="feature-inner">
                                    <div class="feature-icon">
                                        <img src="{{ asset('img/transfer.png') }}" alt="TRANSFERRED">
                                    </div>
                                    <h3 class="feature-title">TRANSFERRED</h3>
                                    <p class="text-sm">Create transferred products from the finished products. Dynamic showrooms and calculation of transfer quantities for each transfer.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="newsletter section">
                <div class="container-sm">
                    <div class="newsletter-inner section-inner">
                        <div class="newsletter-header text-center is-revealing">
                            <h2 class="section-title mt-0">Quick Support</h2>
                            <p class="section-paragraph">We are available for freelance work. If you have any questions that are beyond the scope of this help file, please feel free to inform us to contact us via this mail <a href="mailto:">support@codeshaper.net</a>.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer text-light">
            <div class="container">
                <div class="site-footer-inner">
                    <div class="brand footer-brand">
                        <a href="#">
                            <img src="{{ asset('img/logo-main.svg') }}">
                        </a>
                    </div>
                    <ul class="footer-links list-reset">
                        <li>
                            <a href="https://codeshaper.net/about-us" target="__blank">About us</a>
                        </li>
                        <li>
                            <a href="https://codeshaper.net/contact-us" target="__blank">Contact</a>
                        </li>
                        <li>
                            <a href="https://codeshaper.net/portfolios" target="__blank">Projects</a>
                        </li>
                    </ul>
                    <ul class="footer-social-links list-reset">
                        <li>
                            <a href="https://www.facebook.com/Codeshaperbd/" target="__blank">
                                <span class="screen-reader-text">Facebook</span>
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.023 16L6 9H3V6h3V4c0-2.7 1.672-4 4.08-4 1.153 0 2.144.086 2.433.124v2.821h-1.67c-1.31 0-1.563.623-1.563 1.536V6H13l-1 3H9.28v7H6.023z" fill="#FFFFFF"/>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/codeshaperbd"  target="__blank">
                                <span class="screen-reader-text">Twitter</span>
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 3c-.6.3-1.2.4-1.9.5.7-.4 1.2-1 1.4-1.8-.6.4-1.3.6-2.1.8-.6-.6-1.5-1-2.4-1-1.7 0-3.2 1.5-3.2 3.3 0 .3 0 .5.1.7-2.7-.1-5.2-1.4-6.8-3.4-.3.5-.4 1-.4 1.7 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.5-.4C.7 7.7 1.8 9 3.3 9.3c-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3.1 2.3-1.1.9-2.5 1.4-4.1 1.4H0c1.5.9 3.2 1.5 5 1.5 6 0 9.3-5 9.3-9.3v-.4C15 4.3 15.6 3.7 16 3z" fill="#FFFFFF"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                    <div class="footer-copyright">&copy; {{ date('Y') }} Codeshaper, all rights reserved</div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
