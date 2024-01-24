@extends('layouts.app')

@php
    function rupiah($angka){
        $hasil_rupiah = "Rp" . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }
@endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">


                <!-- Row start -->
                <div class="row">
                    <div class="col-sm-12 col-12">

                        <!-- Card start -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">e-Receipt #{{ $transactions[0]->order_id }}</div>


                                <button class="btn btn-primary bi bi-filetype-pdf" print()>

                                </button>
                            </div>
                            <div class="card-body">
                                <p>Date : {{ $transactions[0]->created_at }}</p>

                                @foreach ($transactions as $transaction)
                                <div class="row">
                                    <div class="col">
                                        <div class=" fw-bold">
                                            {{-- <img src="{{ $transaction->product->photo }}" width="70" height="70" alt=""> --}}
                                            {{ $transaction->product->name }}

                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                {{ $transaction->quantity }} x
                                            </div>
                                            <div class="col text-end">
                                                {{rupiah($transaction->price * $transaction->quantity)}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                            <div class="card-footer" >
                                <div class="row fw-bold">
                                    <div class="col" >
                                        <span>Total biaya : </span>
                                    </div>
                                    <div class="col text-end">
                                        <span>{{rupiah($total_biaya)}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card end -->
                    </div>
                </div>
                <!-- Row end -->
            </div>
        </div>
    </div>
@endsection

<script>print()</script>