@extends('layouts.admin')

@section('extra-style')
<link href="{{ asset('css/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2/select2-bootstrap4.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('General Settings') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.setup') }}">{{ __('Setup') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('General Settings') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
        <div class="col-md-12">
            @include('admin.includes.alert')
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('General Settings') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.setup') }}" class="btn btn-block btn-primary">
                        <i class="fas fa-long-arrow-alt-left"></i> {{ __('Go Back') }}
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <form class="form-horizontal" action="{{ route('admin.setup.general.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="companyName">{{ __('Company Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('companyName') is-invalid @enderror" id="companyName" name="companyName" placeholder="{{ __('Company Name') }}" value="{{ $settings->compnayName }}" required>
                                @error('companyName')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="compnayTagline">{{ __('Compnay Tagline') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('compnayTagline') is-invalid @enderror" id="compnayTagline" name="compnayTagline" placeholder="{{ __('Compnay Tagline') }}" value="{{ $settings->compnayTagline }}" required>
                                @error('compnayTagline')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="email" class="col-form-label">{{ __('Email Address') }}<span class="required-field">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('Email Address') }}" value="{{ $settings->email }}" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone" class="col-form-label">{{ __('Phone Number') }}</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="{{ __('Phone Number') }}" value="{{ $settings->phone }}">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="address" class="col-form-label">{{ __('Address') }}<span class="required-field">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="{{ __('Address') }}" required>{{ $settings->address }}</textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="currencyName" class="col-form-label">{{ __('Currency Name') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('currencyName') is-invalid @enderror" id="currencyName" name="currencyName" placeholder="{{ __('Currency Name') }}" value="{{ $settings->currencyName }}" required>
                                @error('currencyName')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="currencySymbol" class="col-form-label">{{ __('Currency Symbol') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('currencySymbol') is-invalid @enderror" id="currencySymbol" name="currencySymbol" placeholder="{{ __('Currency Symbol') }}" value="{{ $settings->currencySymbol }}" required>
                                @error('currencySymbol')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="currencyPosition" class="col-form-label">{{ __('Currency Position') }}<span class="required-field">*</span></label>
                                <select class="form-control @error('currencyPosition') is-invalid @enderror" id="currencyPosition" name="currencyPosition"  required>
                                    <option value="left" {{ $settings->currencyPosition == 'left' ? 'selected' : '' }}>{{ __('Left Align') }}</option>
                                    <option value="right" {{ $settings->currencyPosition == 'right' ? 'selected' : '' }}>{{ __('Right Align') }}</option>
                                </select>
                                @error('currencyPosition')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="timezone" class="col-form-label">{{ __('Timezone') }}<span class="required-field">*</span></label>
                                <select class="advance-select-box form-control @error('purcahseProduct') is-invalid @enderror" id="timezone" name="timezone" required>
                                    <option value="Africa/Abidjan" {{ $settings->timezone == 'Africa/Abidjan' ? 'selected' : '' }}>Africa/Abidjan</option>
                                    <option value="Africa/Accra" {{ $settings->timezone == 'Africa/Accra' ? 'selected' : '' }}>Africa/Accra</option>
                                    <option value="Africa/Addis_Ababa" {{ $settings->timezone == 'Africa/Addis_Ababa' ? 'selected' : '' }}>Africa/Addis Ababa</option>
                                    <option value="Africa/Algiers" {{ $settings->timezone == 'Africa/Algiers' ? 'selected' : '' }}>Africa/Algiers</option>
                                    <option value="Africa/Asmara" {{ $settings->timezone == 'Africa/Asmara' ? 'selected' : '' }}>Africa/Asmara</option>
                                    <option value="Africa/Bamako" {{ $settings->timezone == 'Africa/Bamako' ? 'selected' : '' }}>Africa/Bamako</option>
                                    <option value="Africa/Bangui" {{ $settings->timezone == 'Africa/Bangui' ? 'selected' : '' }}>Africa/Bangui</option>
                                    <option value="Africa/Banjul" {{ $settings->timezone == 'Africa/Banjul' ? 'selected' : '' }}>Africa/Banjul</option>
                                    <option value="Africa/Bissau" {{ $settings->timezone == 'Africa/Bissau' ? 'selected' : '' }}>Africa/Bissau</option>
                                    <option value="Africa/Blantyre" {{ $settings->timezone == 'Africa/Blantyre' ? 'selected' : '' }}>Africa/Blantyre</option>
                                    <option value="Africa/Brazzaville" {{ $settings->timezone == 'Africa/Brazzaville' ? 'selected' : '' }}>Africa/Brazzaville</option>
                                    <option value="Africa/Bujumbura" {{ $settings->timezone == 'Africa/Bujumbura' ? 'selected' : '' }}>Africa/Bujumbura</option>
                                    <option value="Africa/Cairo" {{ $settings->timezone == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo</option>
                                    <option value="Africa/Casablanca" {{ $settings->timezone == 'Africa/Casablanca' ? 'selected' : '' }}>Africa/Casablanca</option>
                                    <option value="Africa/Ceuta" {{ $settings->timezone == 'Africa/Ceuta' ? 'selected' : '' }}>Africa/Ceuta</option>
                                    <option value="Africa/Conakry" {{ $settings->timezone == 'Africa/Conakry' ? 'selected' : '' }}>Africa/Conakry</option>
                                    <option value="Africa/Dakar" {{ $settings->timezone == 'Africa/Dakar' ? 'selected' : '' }}>Africa/Dakar</option>
                                    <option value="Africa/Dar_es_Salaam" {{ $settings->timezone == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar es Salaam</option>
                                    <option value="Africa/Djibouti" {{ $settings->timezone == 'Africa/Djibouti' ? 'selected' : '' }}>Africa/Djibouti</option>
                                    <option value="Africa/Douala" {{ $settings->timezone == 'Africa/Douala' ? 'selected' : '' }}>Africa/Douala</option>
                                    <option value="Africa/El_Aaiun" {{ $settings->timezone == 'Africa/El_Aaiun' ? 'selected' : '' }}>Africa/El Aaiun</option>
                                    <option value="Africa/Freetown" {{ $settings->timezone == 'Africa/Freetown' ? 'selected' : '' }}>Africa/Freetown</option>
                                    <option value="Africa/Gaborone" {{ $settings->timezone == 'Africa/Gaborone' ? 'selected' : '' }}>Africa/Gaborone</option>
                                    <option value="Africa/Harare" {{ $settings->timezone == 'Africa/Harare' ? 'selected' : '' }}>Africa/Harare</option>
                                    <option value="Africa/Johannesburg" {{ $settings->timezone == 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg</option>
                                    <option value="Africa/Juba" {{ $settings->timezone == 'Africa/Juba' ? 'selected' : '' }}>Africa/Juba</option>
                                    <option value="Africa/Kampala" {{ $settings->timezone == 'Africa/Kampala' ? 'selected' : '' }}>Africa/Kampala</option>
                                    <option value="Africa/Khartoum" {{ $settings->timezone == 'Africa/Khartoum' ? 'selected' : '' }}>Africa/Khartoum</option>
                                    <option value="Africa/Kigali" {{ $settings->timezone == 'Africa/Kigali' ? 'selected' : '' }}>Africa/Kigali</option>
                                    <option value="Africa/Kinshasa" {{ $settings->timezone == 'Africa/Kinshasa' ? 'selected' : '' }}>Africa/Kinshasa</option>
                                    <option value="Africa/Lagos" {{ $settings->timezone == 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos</option>
                                    <option value="Africa/Libreville" {{ $settings->timezone == 'Africa/Libreville' ? 'selected' : '' }}>Africa/Libreville</option>
                                    <option value="Africa/Lome" {{ $settings->timezone == 'Africa/Lome' ? 'selected' : '' }}>Africa/Lome</option>
                                    <option value="Africa/Luanda" {{ $settings->timezone == 'Africa/Luanda' ? 'selected' : '' }}>Africa/Luanda</option>
                                    <option value="Africa/Lubumbashi" {{ $settings->timezone == 'Africa/Lubumbashi' ? 'selected' : '' }}>Africa/Lubumbashi</option>
                                    <option value="Africa/Lusaka" {{ $settings->timezone == 'Africa/Lusaka' ? 'selected' : '' }}>Africa/Lusaka</option>
                                    <option value="Africa/Malabo" {{ $settings->timezone == 'Africa/Malabo' ? 'selected' : '' }}>Africa/Malabo</option>
                                    <option value="Africa/Maputo" {{ $settings->timezone == 'Africa/Maputo' ? 'selected' : '' }}>Africa/Maputo</option>
                                    <option value="Africa/Maseru" {{ $settings->timezone == 'Africa/Maseru' ? 'selected' : '' }}>Africa/Maseru</option>
                                    <option value="Africa/Mbabane" {{ $settings->timezone == 'Africa/Mbabane' ? 'selected' : '' }}>Africa/Mbabane</option>
                                    <option value="Africa/Mogadishu" {{ $settings->timezone == 'Africa/Mogadishu' ? 'selected' : '' }}>Africa/Mogadishu</option>
                                    <option value="Africa/Monrovia" {{ $settings->timezone == 'Africa/Monrovia' ? 'selected' : '' }}>Africa/Monrovia</option>
                                    <option value="Africa/Nairobi" {{ $settings->timezone == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi</option>
                                    <option value="Africa/Ndjamena" {{ $settings->timezone == 'Africa/Ndjamena' ? 'selected' : '' }}>Africa/Ndjamena</option>
                                    <option value="Africa/Niamey" {{ $settings->timezone == 'Africa/Niamey' ? 'selected' : '' }}>Africa/Niamey</option>
                                    <option value="Africa/Nouakchott" {{ $settings->timezone == 'Africa/Nouakchott' ? 'selected' : '' }}>Africa/Nouakchott</option>
                                    <option value="Africa/Ouagadougou" {{ $settings->timezone == 'Africa/Ouagadougou' ? 'selected' : '' }}>Africa/Ouagadougou</option>
                                    <option value="Africa/Porto-Novo" {{ $settings->timezone == 'Africa/Porto-Novo' ? 'selected' : '' }}>Africa/Porto-Novo</option>
                                    <option value="Africa/Sao_Tome" {{ $settings->timezone == 'Africa/Sao_Tome' ? 'selected' : '' }}>Africa/Sao Tome</option>
                                    <option value="Africa/Tripoli" {{ $settings->timezone == 'Africa/Tripoli' ? 'selected' : '' }}>Africa/Tripoli</option>
                                    <option value="Africa/Tunis" {{ $settings->timezone == 'Africa/Tunis' ? 'selected' : '' }}>Africa/Tunis</option>
                                    <option value="Africa/Windhoek" {{ $settings->timezone == 'Africa/Windhoek' ? 'selected' : '' }}>Africa/Windhoek</option>

                                    <option value="America/Adak" {{ $settings->timezone == 'America/Adak' ? 'selected' : '' }}>America/Adak</option>
                                    <option value="America/Anchorage" {{ $settings->timezone == 'America/Anchorage' ? 'selected' : '' }}>America/Anchorage</option>
                                    <option value="America/Anguilla" {{ $settings->timezone == 'America/Anguilla' ? 'selected' : '' }}>America/Anguilla</option>
                                    <option value="America/Antigua" {{ $settings->timezone == 'America/Antigua' ? 'selected' : '' }}>America/Antigua</option>
                                    <option value="America/Araguaina" {{ $settings->timezone == 'America/Araguaina' ? 'selected' : '' }}>America/Araguaina</option>
                                    <option value="America/Argentina/Buenos_Aires" {{ $settings->timezone == 'America/Argentina/Buenos_Aires' ? 'selected' : '' }}>America/Argentina/Buenos Aires</option>
                                    <option value="America/Argentina/Catamarca" {{ $settings->timezone == 'America/Argentina/Catamarca' ? 'selected' : '' }}>America/Argentina/Catamarca</option>
                                    <option value="America/Argentina/Cordoba" {{ $settings->timezone == 'America/Argentina/Cordoba' ? 'selected' : '' }}>America/Argentina/Cordoba</option>
                                    <option value="America/Argentina/Jujuy" {{ $settings->timezone == 'America/Argentina/Jujuy' ? 'selected' : '' }}>America/Argentina/Jujuy</option>
                                    <option value="America/Argentina/La_Rioja" {{ $settings->timezone == 'America/Argentina/La_Rioja' ? 'selected' : '' }}>America/Argentina/La Rioja</option>
                                    <option value="America/Argentina/Mendoza" {{ $settings->timezone == 'America/Argentina/Mendoza' ? 'selected' : '' }}>America/Argentina/Mendoza</option>
                                    <option value="America/Argentina/Rio_Gallegos" {{ $settings->timezone == 'America/Argentina/Rio_Gallegos' ? 'selected' : '' }}>America/Argentina/Rio Gallegos</option>
                                    <option value="America/Argentina/Salta" {{ $settings->timezone == 'America/Argentina/Salta' ? 'selected' : '' }}>America/Argentina/Salta</option>
                                    <option value="America/Argentina/San_Juan" {{ $settings->timezone == 'America/Argentina/San_Juan' ? 'selected' : '' }}>America/Argentina/San Juan</option>
                                    <option value="America/Argentina/San_Luis" {{ $settings->timezone == 'America/Argentina/San_Luis' ? 'selected' : '' }}>America/Argentina/San Luis</option>
                                    <option value="America/Argentina/Tucuman" {{ $settings->timezone == 'America/Argentina/Tucuman' ? 'selected' : '' }}>America/Argentina/Tucuman</option>
                                    <option value="America/Argentina/Ushuaia" {{ $settings->timezone == 'America/Argentina/Ushuaia' ? 'selected' : '' }}>America/Argentina/Ushuaia</option>
                                    <option value="America/Aruba" {{ $settings->timezone == 'America/Aruba' ? 'selected' : '' }}>America/Aruba</option>
                                    <option value="America/Asuncion" {{ $settings->timezone == 'America/Asuncion' ? 'selected' : '' }}>America/Asuncion</option>
                                    <option value="America/Atikokan" {{ $settings->timezone == 'America/Atikokan' ? 'selected' : '' }}>America/Atikokan</option>
                                    <option value="America/Bahia" {{ $settings->timezone == 'America/Bahia' ? 'selected' : '' }}>America/Bahia</option>
                                    <option value="America/Bahia_Banderas" {{ $settings->timezone == 'America/Bahia_Banderas' ? 'selected' : '' }}>America/Bahia Banderas</option>
                                    <option value="America/Barbados" {{ $settings->timezone == 'America/Barbados' ? 'selected' : '' }}>America/Barbados</option>
                                    <option value="America/Belem" {{ $settings->timezone == 'America/Belem' ? 'selected' : '' }}>America/Belem</option>
                                    <option value="America/Belize" {{ $settings->timezone == 'America/Belize' ? 'selected' : '' }}>America/Belize</option>
                                    <option value="America/Blanc-Sablon" {{ $settings->timezone == 'America/Blanc-Sablon' ? 'selected' : '' }}>America/Blanc-Sablon</option>
                                    <option value="America/Boa_Vista" {{ $settings->timezone == 'America/Boa_Vista' ? 'selected' : '' }}>America/Boa Vista</option>
                                    <option value="America/Bogota" {{ $settings->timezone == 'America/Bogota' ? 'selected' : '' }}>America/Bogota</option>
                                    <option value="America/Boise" {{ $settings->timezone == 'America/Boise' ? 'selected' : '' }}>America/Boise</option>
                                    <option value="America/Cambridge_Bay" {{ $settings->timezone == 'America/Cambridge_Bay' ? 'selected' : '' }}>America/Cambridge Bay</option>
                                    <option value="America/Campo_Grande" {{ $settings->timezone == 'America/Campo_Grande' ? 'selected' : '' }}>America/Campo Grande</option>
                                    <option value="America/Cancun" {{ $settings->timezone == 'America/Cancun' ? 'selected' : '' }}>America/Cancun</option>
                                    <option value="America/Caracas" {{ $settings->timezone == 'America/Caracas' ? 'selected' : '' }}>America/Caracas</option>
                                    <option value="America/Cayenne" {{ $settings->timezone == 'America/Cayenne' ? 'selected' : '' }}>America/Cayenne</option>
                                    <option value="America/Cayman" {{ $settings->timezone == 'America/Cayman' ? 'selected' : '' }}>America/Cayman</option>
                                    <option value="America/Chicago" {{ $settings->timezone == 'America/Chicago' ? 'selected' : '' }}>America/Chicago</option>
                                    <option value="America/Chihuahua" {{ $settings->timezone == 'America/Chihuahua' ? 'selected' : '' }}>America/Chihuahua</option>
                                    <option value="America/Costa_Rica" {{ $settings->timezone == 'America/Costa_Rica' ? 'selected' : '' }}>America/Costa Rica</option>
                                    <option value="America/Creston" {{ $settings->timezone == 'America/Creston' ? 'selected' : '' }}>America/Creston</option>
                                    <option value="America/Cuiaba" {{ $settings->timezone == 'America/Cuiaba' ? 'selected' : '' }}>America/Cuiaba</option>
                                    <option value="America/Curacao" {{ $settings->timezone == 'America/Curacao' ? 'selected' : '' }}>America/Curacao</option>
                                    <option value="America/Danmarkshavn" {{ $settings->timezone == 'America/Danmarkshavn' ? 'selected' : '' }}>America/Danmarkshavn</option>
                                    <option value="America/Dawson" {{ $settings->timezone == 'America/Dawson' ? 'selected' : '' }}>America/Dawson</option>
                                    <option value="America/Dawson_Creek" {{ $settings->timezone == 'America/Dawson_Creek' ? 'selected' : '' }}>America/Dawson Creek</option>
                                    <option value="America/Denver" {{ $settings->timezone == 'America/Denver' ? 'selected' : '' }}>America/Denver</option>
                                    <option value="America/Detroit" {{ $settings->timezone == 'America/Detroit' ? 'selected' : '' }}>America/Detroit</option>
                                    <option value="America/Dominica" {{ $settings->timezone == 'America/Dominica' ? 'selected' : '' }}>America/Dominica</option>
                                    <option value="America/Edmonton" {{ $settings->timezone == 'America/Edmonton' ? 'selected' : '' }}>America/Edmonton</option>
                                    <option value="America/Eirunepe" {{ $settings->timezone == 'America/Eirunepe' ? 'selected' : '' }}>America/Eirunepe</option>
                                    <option value="America/El_Salvador" {{ $settings->timezone == 'America/El_Salvador' ? 'selected' : '' }}>America/El Salvador</option>
                                    <option value="America/Fort_Nelson" {{ $settings->timezone == 'America/Fort_Nelson' ? 'selected' : '' }}>America/Fort Nelson</option>
                                    <option value="America/Fortaleza" {{ $settings->timezone == 'America/Fortaleza' ? 'selected' : '' }}>America/Fortaleza</option>
                                    <option value="America/Glace_Bay" {{ $settings->timezone == 'America/Glace_Bay' ? 'selected' : '' }}>America/Glace Bay</option>
                                    <option value="America/Godthab" {{ $settings->timezone == 'America/Godthab' ? 'selected' : '' }}>America/Godthab</option>
                                    <option value="America/Goose_Bay" {{ $settings->timezone == 'America/Goose_Bay' ? 'selected' : '' }}>America/Goose Bay</option>
                                    <option value="America/Grand_Turk" {{ $settings->timezone == 'America/Grand_Turk' ? 'selected' : '' }}>America/Grand Turk</option>
                                    <option value="America/Grenada" {{ $settings->timezone == 'America/Grenada' ? 'selected' : '' }}>America/Grenada</option>
                                    <option value="America/Guadeloupe" {{ $settings->timezone == 'America/Guadeloupe' ? 'selected' : '' }}>America/Guadeloupe</option>
                                    <option value="America/Guatemala" {{ $settings->timezone == 'America/Guatemala' ? 'selected' : '' }}>America/Guatemala</option>
                                    <option value="America/Guayaquil" {{ $settings->timezone == 'America/Guayaquil' ? 'selected' : '' }}>America/Guayaquil</option>
                                    <option value="America/Guyana" {{ $settings->timezone == 'America/Guyana' ? 'selected' : '' }}>America/Guyana</option>
                                    <option value="America/Halifax" {{ $settings->timezone == 'America/Halifax' ? 'selected' : '' }}>America/Halifax</option>
                                    <option value="America/Havana" {{ $settings->timezone == 'America/Havana' ? 'selected' : '' }}>America/Havana</option>
                                    <option value="America/Hermosillo" {{ $settings->timezone == 'America/Hermosillo' ? 'selected' : '' }}>America/Hermosillo</option>
                                    <option value="America/Indiana/Indianapolis" {{ $settings->timezone == 'America/Indiana/Indianapolis' ? 'selected' : '' }}>America/Indiana/Indianapolis</option>
                                    <option value="America/Indiana/Knox" {{ $settings->timezone == 'America/Indiana/Knox' ? 'selected' : '' }}>America/Indiana/Knox</option>
                                    <option value="America/Indiana/Marengo" {{ $settings->timezone == 'America/Indiana/Marengo' ? 'selected' : '' }}>America/Indiana/Marengo</option>
                                    <option value="America/Indiana/Petersburg" {{ $settings->timezone == 'America/Indiana/Petersburg' ? 'selected' : '' }}>America/Indiana/Petersburg</option>
                                    <option value="America/Indiana/Tell_City" {{ $settings->timezone == 'America/Indiana/Tell_City' ? 'selected' : '' }}>America/Indiana/Tell City</option>
                                    <option value="America/Indiana/Vevay" {{ $settings->timezone == 'America/Indiana/Vevay' ? 'selected' : '' }}>America/Indiana/Vevay</option>
                                    <option value="America/Indiana/Vincennes" {{ $settings->timezone == 'America/Indiana/Vincennes' ? 'selected' : '' }}>America/Indiana/Vincennes</option>
                                    <option value="America/Indiana/Winamac" {{ $settings->timezone == 'America/Indiana/Winamac' ? 'selected' : '' }}>America/Indiana/Winamac</option>
                                    <option value="America/Inuvik" {{ $settings->timezone == 'America/Inuvik' ? 'selected' : '' }}>America/Inuvik</option>
                                    <option value="America/Iqaluit" {{ $settings->timezone == 'America/Iqaluit' ? 'selected' : '' }}>America/Iqaluit</option>
                                    <option value="America/Jamaica" {{ $settings->timezone == 'America/Jamaica' ? 'selected' : '' }}>America/Jamaica</option>
                                    <option value="America/Juneau" {{ $settings->timezone == 'America/Juneau' ? 'selected' : '' }}>America/Juneau</option>
                                    <option value="America/Kentucky/Louisville" {{ $settings->timezone == 'America/Kentucky/Louisville' ? 'selected' : '' }}>America/Kentucky/Louisville</option>
                                    <option value="America/Kentucky/Monticello" {{ $settings->timezone == 'America/Kentucky/Monticello' ? 'selected' : '' }}>America/Kentucky/Monticello</option>
                                    <option value="America/Kralendijk" {{ $settings->timezone == 'America/Kralendijk' ? 'selected' : '' }}>America/Kralendijk</option>
                                    <option value="America/La_Paz" {{ $settings->timezone == 'America/La_Paz' ? 'selected' : '' }}>America/La Paz</option>
                                    <option value="America/Lima" {{ $settings->timezone == 'America/Lima' ? 'selected' : '' }}>America/Lima</option>
                                    <option value="America/Los_Angeles" {{ $settings->timezone == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los Angeles</option>
                                    <option value="America/Lower_Princes" {{ $settings->timezone == 'America/Lower_Princes' ? 'selected' : '' }}>America/Lower Princes</option>
                                    <option value="America/Maceio" {{ $settings->timezone == 'America/Maceio' ? 'selected' : '' }}>America/Maceio</option>
                                    <option value="America/Managua" {{ $settings->timezone == 'America/Managua' ? 'selected' : '' }}>America/Managua</option>
                                    <option value="America/Manaus" {{ $settings->timezone == 'America/Manaus' ? 'selected' : '' }}>America/Manaus</option>
                                    <option value="America/Marigot" {{ $settings->timezone == 'America/Marigot' ? 'selected' : '' }}>America/Marigot</option>
                                    <option value="America/Martinique" {{ $settings->timezone == 'America/Martinique' ? 'selected' : '' }}>America/Martinique</option>
                                    <option value="America/Matamoros" {{ $settings->timezone == 'America/Matamoros' ? 'selected' : '' }}>America/Matamoros</option>
                                    <option value="America/Mazatlan" {{ $settings->timezone == 'America/Mazatlan' ? 'selected' : '' }}>America/Mazatlan</option>
                                    <option value="America/Menominee" {{ $settings->timezone == 'America/Menominee' ? 'selected' : '' }}>America/Menominee</option>
                                    <option value="America/Merida" {{ $settings->timezone == 'America/Merida' ? 'selected' : '' }}>America/Merida</option>
                                    <option value="America/Metlakatla" {{ $settings->timezone == 'America/Metlakatla' ? 'selected' : '' }}>America/Metlakatla</option>
                                    <option value="America/Mexico_City" {{ $settings->timezone == 'America/Mexico_City' ? 'selected' : '' }}>America/Mexico City</option>
                                    <option value="America/Miquelon" {{ $settings->timezone == 'America/Miquelon' ? 'selected' : '' }}>America/Miquelon</option>
                                    <option value="America/Moncton" {{ $settings->timezone == 'America/Moncton' ? 'selected' : '' }}>America/Moncton</option>
                                    <option value="America/Monterrey" {{ $settings->timezone == 'America/Monterrey' ? 'selected' : '' }}>America/Monterrey</option>
                                    <option value="America/Montevideo" {{ $settings->timezone == 'America/Montevideo' ? 'selected' : '' }}>America/Montevideo</option>
                                    <option value="America/Montserrat" {{ $settings->timezone == 'America/Montserrat' ? 'selected' : '' }}>America/Montserrat</option>
                                    <option value="America/Nassau" {{ $settings->timezone == 'America/Nassau' ? 'selected' : '' }}>America/Nassau</option>
                                    <option value="America/New_York" {{ $settings->timezone == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                                    <option value="America/Nipigon" {{ $settings->timezone == 'America/Nipigon' ? 'selected' : '' }}>America/Nipigon</option>
                                    <option value="America/Nome" {{ $settings->timezone == 'America/Nome' ? 'selected' : '' }}>America/Nome</option>
                                    <option value="America/Noronha" {{ $settings->timezone == 'America/Noronha' ? 'selected' : '' }}>America/Noronha</option>
                                    <option value="America/North_Dakota/Beulah" {{ $settings->timezone == 'America/North_Dakota/Beulah' ? 'selected' : '' }}>America/North Dakota/Beulah</option>
                                    <option value="America/North_Dakota/Center" {{ $settings->timezone == 'America/North_Dakota/Center' ? 'selected' : '' }}>America/North Dakota/Center</option>
                                    <option value="America/North_Dakota/New_Salem" {{ $settings->timezone == 'America/North_Dakota/New_Salem' ? 'selected' : '' }}>America/North Dakota/New Salem</option>
                                    <option value="America/Ojinaga" {{ $settings->timezone == 'America/Ojinaga' ? 'selected' : '' }}>America/Ojinaga</option>
                                    <option value="America/Panama" {{ $settings->timezone == 'America/Panama' ? 'selected' : '' }}>America/Panama</option>
                                    <option value="America/Pangnirtung" {{ $settings->timezone == 'America/Pangnirtung' ? 'selected' : '' }}>America/Pangnirtung</option>
                                    <option value="America/Paramaribo" {{ $settings->timezone == 'America/Paramaribo' ? 'selected' : '' }}>America/Paramaribo</option>
                                    <option value="America/Phoenix" {{ $settings->timezone == 'America/Phoenix' ? 'selected' : '' }}>America/Phoenix</option>
                                    <option value="America/Port-au-Prince" {{ $settings->timezone == 'America/Port-au-Prince' ? 'selected' : '' }}>America/Port-au-Prince</option>
                                    <option value="America/Port_of_Spain" {{ $settings->timezone == 'America/Port_of_Spain' ? 'selected' : '' }}>America/Port of Spain</option>
                                    <option value="America/Porto_Velho" {{ $settings->timezone == 'America/Porto_Velho' ? 'selected' : '' }}>America/Porto Velho</option>
                                    <option value="America/Puerto_Rico" {{ $settings->timezone == 'America/Puerto_Rico' ? 'selected' : '' }}>America/Puerto Rico</option>
                                    <option value="America/Punta_Arenas" {{ $settings->timezone == 'America/Punta_Arenas' ? 'selected' : '' }}>America/Punta Arenas</option>
                                    <option value="America/Rainy_River" {{ $settings->timezone == 'America/Rainy_River' ? 'selected' : '' }}>America/Rainy River</option>
                                    <option value="America/Rankin_Inlet" {{ $settings->timezone == 'America/Rankin_Inlet' ? 'selected' : '' }}>America/Rankin Inlet</option>
                                    <option value="America/Recife" {{ $settings->timezone == 'America/Recife' ? 'selected' : '' }}>America/Recife</option>
                                    <option value="America/Regina" {{ $settings->timezone == 'America/Regina' ? 'selected' : '' }}>America/Regina</option>
                                    <option value="America/Resolute" {{ $settings->timezone == 'America/Resolute' ? 'selected' : '' }}>America/Resolute</option>
                                    <option value="America/Rio_Branco" {{ $settings->timezone == 'America/Rio_Branco' ? 'selected' : '' }}>America/Rio Branco</option>
                                    <option value="America/Santarem" {{ $settings->timezone == 'America/Santarem' ? 'selected' : '' }}>America/Santarem</option>
                                    <option value="America/Santiago" {{ $settings->timezone == 'America/Santiago' ? 'selected' : '' }}>America/Santiago</option>
                                    <option value="America/Santo_Domingo" {{ $settings->timezone == 'America/Santo_Domingo' ? 'selected' : '' }}>America/Santo Domingo</option>
                                    <option value="America/Sao_Paulo" {{ $settings->timezone == 'America/Sao_Paulo' ? 'selected' : '' }}>America/Sao Paulo</option>
                                    <option value="America/Scoresbysund" {{ $settings->timezone == 'America/Scoresbysund' ? 'selected' : '' }}>America/Scoresbysund</option>
                                    <option value="America/Sitka" {{ $settings->timezone == 'America/Sitka' ? 'selected' : '' }}>America/Sitka</option>
                                    <option value="America/St_Barthelemy" {{ $settings->timezone == 'America/St_Barthelemy' ? 'selected' : '' }}>America/St Barthelemy</option>
                                    <option value="America/St_Johns" {{ $settings->timezone == 'America/St_Johns' ? 'selected' : '' }}>America/St Johns</option>
                                    <option value="America/St_Kitts" {{ $settings->timezone == 'America/St_Kitts' ? 'selected' : '' }}>America/St Kitts</option>
                                    <option value="America/St_Lucia" {{ $settings->timezone == 'America/St_Lucia' ? 'selected' : '' }}>America/St Lucia</option>
                                    <option value="America/St_Thomas" {{ $settings->timezone == 'America/St_Thomas' ? 'selected' : '' }}>America/St Thomas</option>
                                    <option value="America/St_Vincent" {{ $settings->timezone == 'America/St_Vincent' ? 'selected' : '' }}>America/St Vincent</option>
                                    <option value="America/Swift_Current" {{ $settings->timezone == 'America/Swift_Current' ? 'selected' : '' }}>America/Swift Current</option>
                                    <option value="America/Tegucigalpa" {{ $settings->timezone == 'America/Tegucigalpa' ? 'selected' : '' }}>America/Tegucigalpa</option>
                                    <option value="America/Thule" {{ $settings->timezone == 'America/Thule' ? 'selected' : '' }}>America/Thule</option>
                                    <option value="America/Thunder_Bay" {{ $settings->timezone == 'America/Thunder_Bay' ? 'selected' : '' }}>America/Thunder Bay</option>
                                    <option value="America/Tijuana" {{ $settings->timezone == 'America/Tijuana' ? 'selected' : '' }}>America/Tijuana</option>
                                    <option value="America/Toronto" {{ $settings->timezone == 'America/Toronto' ? 'selected' : '' }}>America/Toronto</option>
                                    <option value="America/Tortola" {{ $settings->timezone == 'America/Tortola' ? 'selected' : '' }}>America/Tortola</option>
                                    <option value="America/Vancouver" {{ $settings->timezone == 'America/Vancouver' ? 'selected' : '' }}>America/Vancouver</option>
                                    <option value="America/Whitehorse" {{ $settings->timezone == 'America/Whitehorse' ? 'selected' : '' }}>America/Whitehorse</option>
                                    <option value="America/Winnipeg" {{ $settings->timezone == 'America/Winnipeg' ? 'selected' : '' }}>America/Winnipeg</option>
                                    <option value="America/Yakutat" {{ $settings->timezone == 'America/Yakutat' ? 'selected' : '' }}>America/Yakutat</option>
                                    <option value="America/Yellowknife" {{ $settings->timezone == 'America/Yellowknife' ? 'selected' : '' }}>America/Yellowknife</option>


                                    <option value="Asia/Aden" {{ $settings->timezone == 'Asia/Aden' ? 'selected' : '' }}>Asia/Aden</option>
                                    <option value="Asia/Almaty" {{ $settings->timezone == 'Asia/Almaty' ? 'selected' : '' }}>Asia/Almaty</option>
                                    <option value="Asia/Amman" {{ $settings->timezone == 'Asia/Amman' ? 'selected' : '' }}>Asia/Amman</option>
                                    <option value="Asia/Anadyr" {{ $settings->timezone == 'Asia/Anadyr' ? 'selected' : '' }}>Asia/Anadyr</option>
                                    <option value="Asia/Aqtau" {{ $settings->timezone == 'Asia/Aqtau' ? 'selected' : '' }}>Asia/Aqtau</option>
                                    <option value="Asia/Aqtobe" {{ $settings->timezone == 'Asia/Aqtobe' ? 'selected' : '' }}>Asia/Aqtobe</option>
                                    <option value="Asia/Ashgabat" {{ $settings->timezone == 'Asia/Ashgabat' ? 'selected' : '' }}>Asia/Ashgabat</option>
                                    <option value="Asia/Atyrau" {{ $settings->timezone == 'Asia/Atyrau' ? 'selected' : '' }}>Asia/Atyrau</option>
                                    <option value="Asia/Baghdad" {{ $settings->timezone == 'Asia/Baghdad' ? 'selected' : '' }}>Asia/Baghdad</option>
                                    <option value="Asia/Bahrain" {{ $settings->timezone == 'Asia/Bahrain' ? 'selected' : '' }}>Asia/Bahrain</option>
                                    <option value="Asia/Baku" {{ $settings->timezone == 'Asia/Baku' ? 'selected' : '' }}>Asia/Baku</option>
                                    <option value="Asia/Bangkok" {{ $settings->timezone == 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                                    <option value="Asia/Barnaul" {{ $settings->timezone == 'Asia/Barnaul' ? 'selected' : '' }}>Asia/Barnaul</option>
                                    <option value="Asia/Beirut" {{ $settings->timezone == 'Asia/Beirut' ? 'selected' : '' }}>Asia/Beirut</option>
                                    <option value="Asia/Bishkek" {{ $settings->timezone == 'Asia/Bishkek' ? 'selected' : '' }}>Asia/Bishkek</option>
                                    <option value="Asia/Brunei" {{ $settings->timezone == 'Asia/Brunei' ? 'selected' : '' }}>Asia/Brunei</option>
                                    <option value="Asia/Chita" {{ $settings->timezone == 'Asia/Chita' ? 'selected' : '' }}>Asia/Chita</option>
                                    <option value="Asia/Choibalsan" {{ $settings->timezone == 'Asia/Choibalsan' ? 'selected' : '' }}>Asia/Choibalsan</option>
                                    <option value="Asia/Colombo" {{ $settings->timezone == 'Asia/Colombo' ? 'selected' : '' }}>Asia/Colombo</option>
                                    <option value="Asia/Damascus" {{ $settings->timezone == 'Asia/Damascus' ? 'selected' : '' }}>Asia/Damascus</option>
                                    <option value="Asia/Dhaka" {{ $settings->timezone == 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka</option>
                                    <option value="Asia/Dili" {{ $settings->timezone == 'Asia/Dili' ? 'selected' : '' }}>Asia/Dili</option>
                                    <option value="Asia/Dubai" {{ $settings->timezone == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai</option>
                                    <option value="Asia/Dushanbe" {{ $settings->timezone == 'Asia/Dushanbe' ? 'selected' : '' }}>Asia/Dushanbe</option>
                                    <option value="Asia/Famagusta" {{ $settings->timezone == 'Asia/Famagusta' ? 'selected' : '' }}>Asia/Famagusta</option>
                                    <option value="Asia/Gaza" {{ $settings->timezone == 'Asia/Gaza' ? 'selected' : '' }}>Asia/Gaza</option>
                                    <option value="Asia/Hebron" {{ $settings->timezone == 'Asia/Hebron' ? 'selected' : '' }}>Asia/Hebron</option>
                                    <option value="Asia/Ho_Chi_Minh" {{ $settings->timezone == 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>Asia/Ho Chi Minh</option>
                                    <option value="Asia/Hong_Kong" {{ $settings->timezone == 'Asia/Hong_Kong' ? 'selected' : '' }}>Asia/Hong Kong</option>
                                    <option value="Asia/Hovd" {{ $settings->timezone == 'Asia/Hovd' ? 'selected' : '' }}>Asia/Hovd</option>
                                    <option value="Asia/Irkutsk" {{ $settings->timezone == 'Asia/Irkutsk' ? 'selected' : '' }}>Asia/Irkutsk</option>
                                    <option value="Asia/Jakarta" {{ $settings->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                    <option value="Asia/Jayapura" {{ $settings->timezone == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura</option>
                                    <option value="Asia/Jerusalem" {{ $settings->timezone == 'Asia/Jerusalem' ? 'selected' : '' }}>Asia/Jerusalem</option>
                                    <option value="Asia/Kabul" {{ $settings->timezone == 'Asia/Kabul' ? 'selected' : '' }}>Asia/Kabul</option>
                                    <option value="Asia/Kamchatka" {{ $settings->timezone == 'Asia/Kamchatka' ? 'selected' : '' }}>Asia/Kamchatka</option>
                                    <option value="Asia/Karachi" {{ $settings->timezone == 'Asia/Karachi' ? 'selected' : '' }}>Asia/Karachi</option>
                                    <option value="Asia/Kathmandu" {{ $settings->timezone == 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu</option>
                                    <option value="Asia/Khandyga" {{ $settings->timezone == 'Asia/Khandyga' ? 'selected' : '' }}>Asia/Khandyga</option>
                                    <option value="Asia/Kolkata" {{ $settings->timezone == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                    <option value="Asia/Krasnoyarsk" {{ $settings->timezone == 'Asia/Krasnoyarsk' ? 'selected' : '' }}>Asia/Krasnoyarsk</option>
                                    <option value="Asia/Kuala_Lumpur" {{ $settings->timezone == 'Asia/Kuala_Lumpur' ? 'selected' : '' }}>Asia/Kuala Lumpur</option>
                                    <option value="Asia/Kuching" {{ $settings->timezone == 'Asia/Kuching' ? 'selected' : '' }}>Asia/Kuching</option>
                                    <option value="Asia/Kuwait" {{ $settings->timezone == 'Asia/Kuwait' ? 'selected' : '' }}>Asia/Kuwait</option>
                                    <option value="Asia/Macau" {{ $settings->timezone == 'Asia/Macau' ? 'selected' : '' }}>Asia/Macau</option>
                                    <option value="Asia/Magadan" {{ $settings->timezone == 'Asia/Magadan' ? 'selected' : '' }}>Asia/Magadan</option>
                                    <option value="Asia/Makassar" {{ $settings->timezone == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar</option>
                                    <option value="Asia/Manila" {{ $settings->timezone == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila</option>
                                    <option value="Asia/Muscat" {{ $settings->timezone == 'Asia/Muscat' ? 'selected' : '' }}>Asia/Muscat</option>
                                    <option value="Asia/Nicosia" {{ $settings->timezone == 'Asia/Nicosia' ? 'selected' : '' }}>Asia/Nicosia</option>
                                    <option value="Asia/Novokuznetsk" {{ $settings->timezone == 'Asia/Novokuznetsk' ? 'selected' : '' }}>Asia/Novokuznetsk</option>
                                    <option value="Asia/Novosibirsk" {{ $settings->timezone == 'Asia/Novosibirsk' ? 'selected' : '' }}>Asia/Novosibirsk</option>
                                    <option value="Asia/Omsk" {{ $settings->timezone == 'Asia/Omsk' ? 'selected' : '' }}>Asia/Omsk</option>
                                    <option value="Asia/Oral" {{ $settings->timezone == 'Asia/Oral' ? 'selected' : '' }}>Asia/Oral</option>
                                    <option value="Asia/Phnom_Penh" {{ $settings->timezone == 'Asia/Phnom_Penh' ? 'selected' : '' }}>Asia/Phnom Penh</option>
                                    <option value="Asia/Pontianak" {{ $settings->timezone == 'Asia/Pontianak' ? 'selected' : '' }}>Asia/Pontianak</option>
                                    <option value="Asia/Pyongyang" {{ $settings->timezone == 'Asia/Pyongyang' ? 'selected' : '' }}>Asia/Pyongyang</option>
                                    <option value="Asia/Qatar" {{ $settings->timezone == 'Asia/Qatar' ? 'selected' : '' }}>Asia/Qatar</option>
                                    <option value="Asia/Qostanay" {{ $settings->timezone == 'Asia/Qostanay' ? 'selected' : '' }}>Asia/Qostanay</option>
                                    <option value="Asia/Qyzylorda" {{ $settings->timezone == 'Asia/Qyzylorda' ? 'selected' : '' }}>Asia/Qyzylorda</option>
                                    <option value="Asia/Riyadh" {{ $settings->timezone == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh</option>
                                    <option value="Asia/Sakhalin" {{ $settings->timezone == 'Asia/Sakhalin' ? 'selected' : '' }}>Asia/Sakhalin</option>
                                    <option value="Asia/Samarkand" {{ $settings->timezone == 'Asia/Samarkand' ? 'selected' : '' }}>Asia/Samarkand</option>
                                    <option value="Asia/Seoul" {{ $settings->timezone == 'Asia/Seoul' ? 'selected' : '' }}>Asia/Seoul</option>
                                    <option value="Asia/Shanghai" {{ $settings->timezone == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai</option>
                                    <option value="Asia/Singapore" {{ $settings->timezone == 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                                    <option value="Asia/Srednekolymsk" {{ $settings->timezone == 'Asia/Srednekolymsk' ? 'selected' : '' }}>Asia/Srednekolymsk</option>
                                    <option value="Asia/Taipei" {{ $settings->timezone == 'Asia/Taipei' ? 'selected' : '' }}>Asia/Taipei</option>
                                    <option value="Asia/Tashkent" {{ $settings->timezone == 'Asia/Tashkent' ? 'selected' : '' }}>Asia/Tashkent</option>
                                    <option value="Asia/Tbilisi" {{ $settings->timezone == 'Asia/Tbilisi' ? 'selected' : '' }}>Asia/Tbilisi</option>
                                    <option value="Asia/Tehran" {{ $settings->timezone == 'Asia/Tehran' ? 'selected' : '' }}>Asia/Tehran</option>
                                    <option value="Asia/Thimphu" {{ $settings->timezone == 'Asia/Thimphu' ? 'selected' : '' }}>Asia/Thimphu</option>
                                    <option value="Asia/Tokyo" {{ $settings->timezone == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                    <option value="Asia/Tomsk" {{ $settings->timezone == 'Asia/Tomsk' ? 'selected' : '' }}>Asia/Tomsk</option>
                                    <option value="Asia/Ulaanbaatar" {{ $settings->timezone == 'Asia/Ulaanbaatar' ? 'selected' : '' }}>Asia/Ulaanbaatar</option>
                                    <option value="Asia/Urumqi" {{ $settings->timezone == 'Asia/Urumqi' ? 'selected' : '' }}>Asia/Urumqi</option>
                                    <option value="Asia/Ust-Nera" {{ $settings->timezone == 'Asia/Ust-Nera' ? 'selected' : '' }}>Asia/Ust-Nera</option>
                                    <option value="Asia/Vientiane" {{ $settings->timezone == 'Asia/Vientiane' ? 'selected' : '' }}>Asia/Vientiane</option>
                                    <option value="Asia/Vladivostok" {{ $settings->timezone == 'Asia/Vladivostok' ? 'selected' : '' }}>Asia/Vladivostok</option>
                                    <option value="Asia/Yakutsk" {{ $settings->timezone == 'Asia/Yakutsk' ? 'selected' : '' }}>Asia/Yakutsk</option>
                                    <option value="Asia/Yangon" {{ $settings->timezone == 'Asia/Yangon' ? 'selected' : '' }}>Asia/Yangon</option>
                                    <option value="Asia/Yekaterinburg" {{ $settings->timezone == 'Asia/Yekaterinburg' ? 'selected' : '' }}>Asia/Yekaterinburg</option>
                                    <option value="Asia/Yerevan" {{ $settings->timezone == 'Asia/Yerevan' ? 'selected' : '' }}>Asia/Yerevan</option>


                                    <option value="Australia/Adelaide" {{ $settings->timezone == 'Australia/Adelaide' ? 'selected' : '' }}>Australia/Adelaide</option>
                                    <option value="Australia/Brisbane" {{ $settings->timezone == 'Australia/Brisbane' ? 'selected' : '' }}>Australia/Brisbane</option>
                                    <option value="Australia/Broken_Hill" {{ $settings->timezone == 'Australia/Broken_Hill' ? 'selected' : '' }}>Australia/Broken Hill</option>
                                    <option value="Australia/Currie" {{ $settings->timezone == 'Australia/Currie' ? 'selected' : '' }}>Australia/Currie</option>
                                    <option value="Australia/Darwin" {{ $settings->timezone == 'Australia/Darwin' ? 'selected' : '' }}>Australia/Darwin</option>
                                    <option value="Australia/Eucla" {{ $settings->timezone == 'Australia/Eucla' ? 'selected' : '' }}>Australia/Eucla</option>
                                    <option value="Australia/Hobart" {{ $settings->timezone == 'Australia/Hobart' ? 'selected' : '' }}>Australia/Hobart</option>
                                    <option value="Australia/Lindeman" {{ $settings->timezone == 'Australia/Lindeman' ? 'selected' : '' }}>Australia/Lindeman</option>
                                    <option value="Australia/Lord_Howe" {{ $settings->timezone == 'Australia/Lord_Howe' ? 'selected' : '' }}>Australia/Lord Howe</option>
                                    <option value="Australia/Melbourne" {{ $settings->timezone == 'Australia/Melbourne' ? 'selected' : '' }}>Australia/Melbourne</option>
                                    <option value="Australia/Perth" {{ $settings->timezone == 'Australia/Perth' ? 'selected' : '' }}>Australia/Perth</option>
                                    <option value="Australia/Sydney" {{ $settings->timezone == 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney</option>
                                    <option value="Atlantic/Azores" {{ $settings->timezone == 'Australia/Azores' ? 'selected' : '' }}>Atlantic/Azores</option>
                                    <option value="Atlantic/Bermuda" {{ $settings->timezone == 'Australia/Bermuda' ? 'selected' : '' }}>Atlantic/Bermuda</option>
                                    <option value="Atlantic/Canary" {{ $settings->timezone == 'Australia/Canary' ? 'selected' : '' }}>Atlantic/Canary</option>
                                    <option value="Atlantic/Cape_Verde" {{ $settings->timezone == 'Australia/Cape_Verde' ? 'selected' : '' }}>Atlantic/Cape Verde</option>
                                    <option value="Atlantic/Faroe" {{ $settings->timezone == 'Australia/Faroe' ? 'selected' : '' }}>Atlantic/Faroe</option>
                                    <option value="Atlantic/Madeira" {{ $settings->timezone == 'Australia/Madeira' ? 'selected' : '' }}>Atlantic/Madeira</option>
                                    <option value="Atlantic/Reykjavik" {{ $settings->timezone == 'Australia/Reykjavik' ? 'selected' : '' }}>Atlantic/Reykjavik</option>
                                    <option value="Atlantic/South_Georgia" {{ $settings->timezone == 'Australia/South_Georgia' ? 'selected' : '' }}>Atlantic/South Georgia</option>
                                    <option value="Atlantic/St_Helena" {{ $settings->timezone == 'Australia/St_Helena' ? 'selected' : '' }}>Atlantic/St Helena</option>
                                    <option value="Atlantic/Stanley" {{ $settings->timezone == 'Australia/Stanley' ? 'selected' : '' }}>Atlantic/Stanley</option>


                                    <option value="Europe/Amsterdam" {{ $settings->timezone == 'Europe/Amsterdam' ? 'selected' : '' }}>Europe/Amsterdam</option>
                                    <option value="Europe/Andorra" {{ $settings->timezone == 'Europe/Andorra' ? 'selected' : '' }}>Europe/Andorra</option>
                                    <option value="Europe/Astrakhan" {{ $settings->timezone == 'Europe/Astrakhan' ? 'selected' : '' }}>Europe/Astrakhan</option>
                                    <option value="Europe/Athens" {{ $settings->timezone == 'Europe/Athens' ? 'selected' : '' }}>Europe/Athens</option>
                                    <option value="Europe/Belgrade" {{ $settings->timezone == 'Europe/Belgrade' ? 'selected' : '' }}>Europe/Belgrade</option>
                                    <option value="Europe/Berlin" {{ $settings->timezone == 'Europe/Berlin' ? 'selected' : '' }}>Europe/Berlin</option>
                                    <option value="Europe/Bratislava" {{ $settings->timezone == 'Europe/Bratislava' ? 'selected' : '' }}>Europe/Bratislava</option>
                                    <option value="Europe/Brussels" {{ $settings->timezone == 'Europe/Brussels' ? 'selected' : '' }}>Europe/Brussels</option>
                                    <option value="Europe/Bucharest" {{ $settings->timezone == 'Europe/Bucharest' ? 'selected' : '' }}>Europe/Bucharest</option>
                                    <option value="Europe/Budapest" {{ $settings->timezone == 'Europe/Budapest' ? 'selected' : '' }}>Europe/Budapest</option>
                                    <option value="Europe/Busingen" {{ $settings->timezone == 'Europe/Busingen' ? 'selected' : '' }}>Europe/Busingen</option>
                                    <option value="Europe/Chisinau" {{ $settings->timezone == 'Europe/Chisinau' ? 'selected' : '' }}>Europe/Chisinau</option>
                                    <option value="Europe/Copenhagen" {{ $settings->timezone == 'Europe/Copenhagen' ? 'selected' : '' }}>Europe/Copenhagen</option>
                                    <option value="Europe/Dublin" {{ $settings->timezone == 'Europe/Dublin' ? 'selected' : '' }}>Europe/Dublin</option>
                                    <option value="Europe/Gibraltar" {{ $settings->timezone == 'Europe/Gibraltar' ? 'selected' : '' }}>Europe/Gibraltar</option>
                                    <option value="Europe/Guernsey" {{ $settings->timezone == 'Europe/Guernsey' ? 'selected' : '' }}>Europe/Guernsey</option>
                                    <option value="Europe/Helsinki" {{ $settings->timezone == 'Europe/Helsinki' ? 'selected' : '' }}>Europe/Helsinki</option>
                                    <option value="Europe/Isle_of_Man" {{ $settings->timezone == 'Europe/Isle_of_Man' ? 'selected' : '' }}>Europe/Isle of Man</option>
                                    <option value="Europe/Istanbul" {{ $settings->timezone == 'Europe/Istanbul' ? 'selected' : '' }}>Europe/Istanbul</option>
                                    <option value="Europe/Jersey" {{ $settings->timezone == 'Europe/Jersey' ? 'selected' : '' }}>Europe/Jersey</option>
                                    <option value="Europe/Kaliningrad" {{ $settings->timezone == 'Europe/Kaliningrad' ? 'selected' : '' }}>Europe/Kaliningrad</option>
                                    <option value="Europe/Kiev" {{ $settings->timezone == 'Europe/Kiev' ? 'selected' : '' }}>Europe/Kiev</option>
                                    <option value="Europe/Kirov" {{ $settings->timezone == 'Europe/Kirov' ? 'selected' : '' }}>Europe/Kirov</option>
                                    <option value="Europe/Lisbon" {{ $settings->timezone == 'Europe/Lisbon' ? 'selected' : '' }}>Europe/Lisbon</option>
                                    <option value="Europe/Ljubljana" {{ $settings->timezone == 'Europe/Ljubljana' ? 'selected' : '' }}>Europe/Ljubljana</option>
                                    <option value="Europe/London" {{ $settings->timezone == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    <option value="Europe/Luxembourg" {{ $settings->timezone == 'Europe/Luxembourg' ? 'selected' : '' }}>Europe/Luxembourg</option>
                                    <option value="Europe/Madrid" {{ $settings->timezone == 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                                    <option value="Europe/Malta" {{ $settings->timezone == 'Europe/Malta' ? 'selected' : '' }}>Europe/Malta</option>
                                    <option value="Europe/Mariehamn" {{ $settings->timezone == 'Europe/Mariehamn' ? 'selected' : '' }}>Europe/Mariehamn</option>
                                    <option value="Europe/Minsk" {{ $settings->timezone == 'Europe/Minsk' ? 'selected' : '' }}>Europe/Minsk</option>
                                    <option value="Europe/Monaco" {{ $settings->timezone == 'Europe/Monaco' ? 'selected' : '' }}>Europe/Monaco</option>
                                    <option value="Europe/Moscow" {{ $settings->timezone == 'Europe/Moscow' ? 'selected' : '' }}>Europe/Moscow</option>
                                    <option value="Europe/Oslo" {{ $settings->timezone == 'Europe/Oslo' ? 'selected' : '' }}>Europe/Oslo</option>
                                    <option value="Europe/Paris" {{ $settings->timezone == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                    <option value="Europe/Podgorica" {{ $settings->timezone == 'Europe/Podgorica' ? 'selected' : '' }}>Europe/Podgorica</option>
                                    <option value="Europe/Prague" {{ $settings->timezone == 'Europe/Prague' ? 'selected' : '' }}>Europe/Prague</option>
                                    <option value="Europe/Riga" {{ $settings->timezone == 'Europe/Riga' ? 'selected' : '' }}>Europe/Riga</option>
                                    <option value="Europe/Rome" {{ $settings->timezone == 'Europe/Rome' ? 'selected' : '' }}>Europe/Rome</option>
                                    <option value="Europe/Samara" {{ $settings->timezone == 'Europe/Samara' ? 'selected' : '' }}>Europe/Samara</option>
                                    <option value="Europe/San_Marino" {{ $settings->timezone == 'Europe/San_Marino' ? 'selected' : '' }}>Europe/San Marino</option>
                                    <option value="Europe/Sarajevo" {{ $settings->timezone == 'Europe/Sarajevo' ? 'selected' : '' }}>Europe/Sarajevo</option>
                                    <option value="Europe/Saratov" {{ $settings->timezone == 'Europe/Saratov' ? 'selected' : '' }}>Europe/Saratov</option>
                                    <option value="Europe/Simferopol" {{ $settings->timezone == 'Europe/Simferopol' ? 'selected' : '' }}>Europe/Simferopol</option>
                                    <option value="Europe/Skopje" {{ $settings->timezone == 'Europe/Busingen' ? 'selected' : '' }}>Europe/Skopje</option>
                                    <option value="Europe/Sofia" {{ $settings->timezone == 'Europe/Sofia' ? 'selected' : '' }}>Europe/Sofia</option>
                                    <option value="Europe/Stockholm" {{ $settings->timezone == 'Europe/Stockholm' ? 'selected' : '' }}>Europe/Stockholm</option>
                                    <option value="Europe/Tallinn" {{ $settings->timezone == 'Europe/Tallinn' ? 'selected' : '' }}>Europe/Tallinn</option>
                                    <option value="Europe/Tirane" {{ $settings->timezone == 'Europe/Tirane' ? 'selected' : '' }}>Europe/Tirane</option>
                                    <option value="Europe/Ulyanovsk" {{ $settings->timezone == 'Europe/Ulyanovsk' ? 'selected' : '' }}>Europe/Ulyanovsk</option>
                                    <option value="Europe/Uzhgorod" {{ $settings->timezone == 'Europe/Uzhgorod' ? 'selected' : '' }}>Europe/Uzhgorod</option>
                                    <option value="Europe/Vaduz" {{ $settings->timezone == 'Europe/Vaduz' ? 'selected' : '' }}>Europe/Vaduz</option>
                                    <option value="Europe/Vatican" {{ $settings->timezone == 'Europe/Vatican' ? 'selected' : '' }}>Europe/Vatican</option>
                                    <option value="Europe/Vienna" {{ $settings->timezone == 'Europe/Vienna' ? 'selected' : '' }}>Europe/Vienna</option>
                                    <option value="Europe/Vilnius" {{ $settings->timezone == 'Europe/Vilnius' ? 'selected' : '' }}>Europe/Vilnius</option>
                                    <option value="Europe/Volgograd" {{ $settings->timezone == 'Europe/Volgograd' ? 'selected' : '' }}>Europe/Volgograd</option>
                                    <option value="Europe/Warsaw" {{ $settings->timezone == 'Europe/Warsaw' ? 'selected' : '' }}>Europe/Warsaw</option>
                                    <option value="Europe/Zagreb" {{ $settings->timezone == 'Europe/Zagreb' ? 'selected' : '' }}>Europe/Zagreb</option>
                                    <option value="Europe/Zaporozhye" {{ $settings->timezone == 'Europe/Zaporozhye' ? 'selected' : '' }}>Europe/Zaporozhye</option>
                                    <option value="Europe/Zurich" {{ $settings->timezone == 'Europe/Zurich' ? 'selected' : '' }}>Europe/Zurich</option>

                                    <option value="Indian/Antananarivo" {{ $settings->timezone == 'Indian/Antananarivo' ? 'selected' : '' }}>Indian/Antananarivo</option>
                                    <option value="Indian/Chagos" {{ $settings->timezone == 'Indian/Chagos' ? 'selected' : '' }}>Indian/Chagos</option>
                                    <option value="Indian/Christmas" {{ $settings->timezone == 'Indian/Christmas' ? 'selected' : '' }}>Indian/Christmas</option>
                                    <option value="Indian/Cocos" {{ $settings->timezone == 'Indian/Cocos' ? 'selected' : '' }}>Indian/Cocos</option>
                                    <option value="Indian/Comoro" {{ $settings->timezone == 'Indian/Comoro' ? 'selected' : '' }}>Indian/Comoro</option>
                                    <option value="Indian/Kerguelen" {{ $settings->timezone == 'Indian/Kerguelen' ? 'selected' : '' }}>Indian/Kerguelen</option>
                                    <option value="Indian/Mahe" {{ $settings->timezone == 'Indian/Mahe' ? 'selected' : '' }}>Indian/Mahe</option>
                                    <option value="Indian/Maldives" {{ $settings->timezone == 'Indian/Maldives' ? 'selected' : '' }}>Indian/Maldives</option>
                                    <option value="Indian/Mauritius" {{ $settings->timezone == 'Indian/Mauritius' ? 'selected' : '' }}>Indian/Mauritius</option>
                                    <option value="Indian/Mayotte" {{ $settings->timezone == 'Indian/Mayotte' ? 'selected' : '' }}>Indian/Mayotte</option>
                                    <option value="Indian/Reunion" {{ $settings->timezone == 'Indian/Reunion' ? 'selected' : '' }}>Indian/Reunion</option>

                                    <option value="Pacific/Apia" {{ $settings->timezone == 'Pacific/Apia' ? 'selected' : '' }}>Pacific/Apia</option>
                                    <option value="Pacific/Auckland" {{ $settings->timezone == 'Pacific/Auckland' ? 'selected' : '' }}>Pacific/Auckland</option>
                                    <option value="Pacific/Bougainville" {{ $settings->timezone == 'Pacific/Bougainville' ? 'selected' : '' }}>Pacific/Bougainville</option>
                                    <option value="Pacific/Chatham" {{ $settings->timezone == 'Pacific/Chatham' ? 'selected' : '' }}>Pacific/Chatham</option>
                                    <option value="Pacific/Chuuk" {{ $settings->timezone == 'Pacific/Chuuk' ? 'selected' : '' }}>Pacific/Chuuk</option>
                                    <option value="Pacific/Easter" {{ $settings->timezone == 'Pacific/Easter' ? 'selected' : '' }}>Pacific/Easter</option>
                                    <option value="Pacific/Efate" {{ $settings->timezone == 'Pacific/Efate' ? 'selected' : '' }}>Pacific/Efate</option>
                                    <option value="Pacific/Enderbury" {{ $settings->timezone == 'Pacific/Enderbury' ? 'selected' : '' }}>Pacific/Enderbury</option>
                                    <option value="Pacific/Fakaofo" {{ $settings->timezone == 'Pacific/Fakaofo' ? 'selected' : '' }}>Pacific/Fakaofo</option>
                                    <option value="Pacific/Fiji" {{ $settings->timezone == 'Pacific/Fiji' ? 'selected' : '' }}>Pacific/Fiji</option>
                                    <option value="Pacific/Funafuti" {{ $settings->timezone == 'Pacific/Funafuti' ? 'selected' : '' }}>Pacific/Funafuti</option>
                                    <option value="Pacific/Galapagos" {{ $settings->timezone == 'Pacific/Galapagos' ? 'selected' : '' }}>Pacific/Galapagos</option>
                                    <option value="Pacific/Gambier" {{ $settings->timezone == 'Pacific/Gambier' ? 'selected' : '' }}>Pacific/Gambier</option>
                                    <option value="Pacific/Guadalcanal" {{ $settings->timezone == 'Pacific/Guadalcanal' ? 'selected' : '' }}>Pacific/Guadalcanal</option>
                                    <option value="Pacific/Guam" {{ $settings->timezone == 'Pacific/Guam' ? 'selected' : '' }}>Pacific/Guam</option>
                                    <option value="Pacific/Honolulu" {{ $settings->timezone == 'Pacific/Honolulu' ? 'selected' : '' }}>Pacific/Honolulu</option>
                                    <option value="Pacific/Kiritimati" {{ $settings->timezone == 'Pacific/Kiritimati' ? 'selected' : '' }}>Pacific/Kiritimati</option>
                                    <option value="Pacific/Kosrae" {{ $settings->timezone == 'Pacific/Kosrae' ? 'selected' : '' }}>Pacific/Kosrae</option>
                                    <option value="Pacific/Kwajalein" {{ $settings->timezone == 'Pacific/Kwajalein' ? 'selected' : '' }}>Pacific/Kwajalein</option>
                                    <option value="Pacific/Majuro" {{ $settings->timezone == 'Pacific/Majuro' ? 'selected' : '' }}>Pacific/Majuro</option>
                                    <option value="Pacific/Marquesas" {{ $settings->timezone == 'Pacific/Marquesas' ? 'selected' : '' }}>Pacific/Marquesas</option>
                                    <option value="Pacific/Midway" {{ $settings->timezone == 'Pacific/Midway' ? 'selected' : '' }}>Pacific/Midway</option>
                                    <option value="Pacific/Nauru" {{ $settings->timezone == 'Pacific/Nauru' ? 'selected' : '' }}>Pacific/Nauru</option>
                                    <option value="Pacific/Niue" {{ $settings->timezone == 'Pacific/Niue' ? 'selected' : '' }}>Pacific/Niue</option>
                                    <option value="Pacific/Norfolk" {{ $settings->timezone == 'Pacific/Norfolk' ? 'selected' : '' }}>Pacific/Norfolk</option>
                                    <option value="Pacific/Noumea" {{ $settings->timezone == 'Pacific/Noumea' ? 'selected' : '' }}>Pacific/Noumea</option>
                                    <option value="Pacific/Pago_Pago" {{ $settings->timezone == 'Pacific/Pago_Pago' ? 'selected' : '' }}>Pacific/Pago Pago</option>
                                    <option value="Pacific/Palau" {{ $settings->timezone == 'Pacific/Palau' ? 'selected' : '' }}>Pacific/Palau</option>
                                    <option value="Pacific/Pitcairn" {{ $settings->timezone == 'Pacific/Pitcairn' ? 'selected' : '' }}>Pacific/Pitcairn</option>
                                    <option value="Pacific/Pohnpei" {{ $settings->timezone == 'Pacific/Pohnpei' ? 'selected' : '' }}>Pacific/Pohnpei</option>
                                    <option value="Pacific/Port_Moresby" {{ $settings->timezone == 'Pacific/Port_Moresby' ? 'selected' : '' }}>Pacific/Port Moresby</option>
                                    <option value="Pacific/Rarotonga" {{ $settings->timezone == 'Pacific/Rarotonga' ? 'selected' : '' }}>Pacific/Rarotonga</option>
                                    <option value="Pacific/Saipan" {{ $settings->timezone == 'Pacific/Saipan' ? 'selected' : '' }}>Pacific/Saipan</option>
                                    <option value="Pacific/Tahiti" {{ $settings->timezone == 'Pacific/Tahiti' ? 'selected' : '' }}>Pacific/Tahiti</option>
                                    <option value="Pacific/Tarawa" {{ $settings->timezone == 'Pacific/Tarawa' ? 'selected' : '' }}>Pacific/Tarawa</option>
                                    <option value="Pacific/Tongatapu" {{ $settings->timezone == 'Pacific/Tongatapu' ? 'selected' : '' }}>Pacific/Tongatapu</option>
                                    <option value="Pacific/Wake" {{ $settings->timezone == 'Pacific/Wake' ? 'selected' : '' }}>Pacific/Wake</option>
                                    <option value="Pacific/Wallis" {{ $settings->timezone == 'Pacific/Wallis' ? 'selected' : '' }}>Pacific/Wallis</option>
                                </select>
                                @error('purcahseProduct')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="purchaseCodePrefix" class="col-form-label">{{ __('Purchase Code Prefix') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('purchaseCodePrefix') is-invalid @enderror" id="purchaseCodePrefix" name="purchaseCodePrefix" placeholder="Purchase Code Prefix" value="{{ $settings->codePefix }}" required>
                                @error('purchaseCodePrefix')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="processingCodePrefix" class="col-form-label">{{ __('Processing Code Prefix') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('processingCodePrefix') is-invalid @enderror" id="processingCodePrefix" name="processingCodePrefix" placeholder="Purchase Code Prefix" value="{{ $settings->processingCodePefix }}" required>
                                @error('processingCodePrefix')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="finishedCodePrefix" class="col-form-label">{{ __('Finished Code Prefix') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('finishedCodePrefix') is-invalid @enderror" id="finishedCodePrefix" name="finishedCodePrefix" placeholder="Purchase Code Prefix" value="{{ $settings->finishedCodePefix }}" required>
                                @error('finishedCodePrefix')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="transferredCodePefix" class="col-form-label">{{ __('Rransferred Code Prefix') }}<span class="required-field">*</span></label>
                                <input type="text" class="form-control @error('transferredCodePefix') is-invalid @enderror" id="transferredCodePefix" name="transferredCodePefix" placeholder="Purchase Code Prefix" value="{{ $settings->transferredCodePefix }}" required>
                                @error('transferredCodePefix')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="logo" class="col-form-label">{{ __('Logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="attached-image" name="logo">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('logo')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview logo-image">
                                    <img src="{{ asset('img/'.$settings->logo) }}" id="attached-preview-img" class="mt-3" />
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="smallLogo" class="col-form-label">{{ __('Small Logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('smallLogo') is-invalid @enderror" id="smallLogo" name="smallLogo">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('smallLogo')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="samll-logo-preview">
                                    <img src="{{ asset('img/'.$settings->smallLogo) }}" id="small-logo-preview-img" class="mt-3" />
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="favicon" class="col-form-label">{{ __('Favicon') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" id="favicon" name="favicon">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('favicon')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview favicon-image">
                                    <img src="{{ asset($settings->favicon) }}" id="favicon-preview-img" class="mt-3" />
                                </div>
                            </div>
                        </div>

                        {{--Dark Logo--}}
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="" class="col-form-label">{{ __('Dark Logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('dark_logo') is-invalid @enderror" id="dark-logo" name="dark_logo">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('dark_logo')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview logo-image">
                                    <img src="{{ $settings->darkLogo}}" id="dark-logo-preview-img" class="mt-3" />
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="smallDarkLogo" class="col-form-label">{{ __('Small Dark Logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('small_dark_logo') is-invalid @enderror" id="small-dark-logo" name="small_dark_logo">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    @error('small_dark_logo')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="image-preview">
                                    <img src="{{ $settings->smallDarkLogo }}" id="small-dark-logo-preview-img" class="mt-3" />
                                </div>

                            </div>
                            <div class="col-md-4 form-group"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="copyright" class="col-form-label">{{ __('Copyright') }}</label>
                                <input type="text" class="form-control @error('copyright') is-invalid @enderror" id="copyright" name="copyright" placeholder="{{ __('copyright') }}" value="{{ $settings->copyright }}">
                                @error('copyright')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('extra-script')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script>
    (function(){
        "use strict";
        $(document).ready(function(){
            // samll logo preview
            $("#smallLogo").change(function(){
                readLogoURL(this);
            });
            function readLogoURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#small-logo-preview-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            // favicon preview
            $("#favicon").change(function(){
                readFaviconURL(this);
            });
            function readFaviconURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#favicon-preview-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // dark-logo preview
            $("#dark-logo").change(function(){
                readDarkLogoURL(this);
            });
            function readDarkLogoURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#dark-logo-preview-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // small-dark-logo preview
            $("#small-dark-logo").change(function(){
                readSmallDarkLogoURL(this);
            });
            function readSmallDarkLogoURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#small-dark-logo-preview-img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    })();
</script>
@endsection

