@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('components.message')
                    <div class="card-header">Совершить отложенный перевод
                        <p>Пользователь {{$user->getName()}}, баланс = {{$user->getBalance()}}</p>
                    </div>
                    <div class="card-body">
                        @include('components.errors')
                        <form method="POST" action="{{route('remittance.store')}}">
                            @csrf
                            <div class="form-group ">
                                <label for="value">Количество</label>
                                <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Дата</label>
                                <input type="datetime-local" class="form-control" id="do_at" name="do_at"
                                       value="{{$now->format('Y-m-d\TH:i')}}"
                                       required>
                                <small>Округление до часов всегда в меньшую сторону</small>
                            </div>
                            <div class="form-group">
                                <label for="toUser">Пользователю</label>
                                <select class="form-control" id="recipient_id" name="recipient_id" required>
                                    @foreach($users as $recipient)
                                        @if($user->getId() != $recipient->getId())
                                            <option value="{{$recipient->getId()}}">{{$recipient->getName()}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="payer_id" name="payer_id" value="{{$user->getId()}}">
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
