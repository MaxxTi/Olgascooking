@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Подтверждение Вашей электронной почты</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            На ваш электронный адрес была отправлена свежая проверочная ссылка
                        </div>
                    @endif
                    
                    <p>Пожалуйста, проверьте свою электронную почту на наличие проверочной ссылки
                    Если вы не получили это письмо, <a href="{{ route('verification.resend') }}">нажмите здесь, чтобы запросить еще одину</a>.</p>
                    <p><a href="{{ route('mainPage.showPage') }}">Перейти на сайт без подтверждения электронной почты</a> (подтвердить электроную почту можно в профиле).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
