@extends('layouts.app')

@php
    function rupiah($angka)
    {
        $hasil_rupiah = 'Rp' . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
@endphp

@section('content')
<div class="container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
        <div class="alert alert-danger fs-6 fw-semibold" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12">

            @if (Auth::user()->role == 'siswa')
                
                <div class="card shadow-sm">
                    <div class="card-header fs-5 fw-bolder">
                       Balance
                    </div>
                    <div class="card-body">                       
                    <div class="row">
                            <div class="col">
                                <div class="">
                                    <h4 class="card-text">{{ rupiah($saldo) }}</h4>
                                </div>
                            </div>
                            <div class="col text-end">
                                <button type="button" class="btn btn-success px-5" data-bs-target="#formTransfer" data-bs-toggle="modal">Withdraw</button>
                                <button type="button" class="btn btn-success px-5" data-bs-target="#formTopUp" data-bs-toggle="modal">Top Up</button>

                                <!-- Modal -->
                                <form action="{{ route('topupNow') }}" method="post">
                                    @csrf
                                    <div class="modal fade" id="formTopUp" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Masukkan Nominal</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input type="number" name="credit" id=""
                                                            class="form-control" min="10000" value="10000">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Top Up Now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Modal Tarik Tunai -->
                                <form action="{{ route('withdrawNow') }}" method="post">
                                    @csrf
                                    <div class="modal fade" id="formTransfer" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Withdraw</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input type="number" name="debit" id=""
                                                            class="form-control" min="10000" value="10000">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Withdraw Now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- End Modal Tarik Tunai -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row start -->
                <div class="row">
                    <div class="col-sm-12 col-lg-4 ">
                        <div class="card">
                            <div class="card-header">
                                <div class="fw-semibold fs-5">Keranjang</div>
                            </div>
                            <div class="card-body">
                                @foreach ($carts as $key => $cart)
                                    <div class="row">
                                        <ul class="card p-3 mb-2">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="col fw-semibold">
                                                    {{ $cart->product->name }} | {{ $cart->quantity }} | {{ $cart->price * $cart->quantity }}
                                                </div>
                                                <div class="col d-flex justify-content-end align-items-center">
                                                    <form action="{{ route('deleteBaskets',['id'=>$cart->id]) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash2-fill"></i></button>
                                                    </form>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                @endforeach
                            </div>
                            <div class="card-footer">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="fw-bold fs-6">Total Biaya :</span>
                                        <h4 class="">{{ rupiah($total_biaya) }}</h4>
                                    </div>
                                    <div class="col text-end">
                                        <form action="{{ route('payNow')}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn {{ $saldo < $total_biaya ? 'btn-secondary' : 'btn-success' }} ">Checkout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card mt-1">
                                <div class="card-header fs-6 fw-bold">
                                        Riwayat Transaksi
                                </div>
                                <div class="card-body">
                                    <ul class="list-group border-0">   
                                        @foreach ($transactions as $key => $transaction)    
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="col fw-bold">
                                                            {{ $transaction[0]->order_id }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col text-secondary" style="font-size: 12px">
                                                            {{ $transaction[0]->created_at }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col d-flex justify-content-end align-items-center">
                                                    <a href="{{ route('download', ['order_id' => $transaction[0]->order_id]) }}" class="btn btn-primary">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </a>
                                                </div>
                                            </div>                                 
                                        @endforeach                                         
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="">

                                <div class="card bg-white shadow-sm border-0">
                                    <div class="card-header border-0 fs-6 fw-bold">
                                        Mutasi Transaction
                                    </div>

                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach ($mutasi as $data)
                                                <li class="list-group-item">
                                                    <div class="d-flex  justify-content-between align-items-center">
                                                        <div>
                                                            @if ($data->credit)
                                                                <span class="text-success fw-bold">Credit : </span>
                                                                {{ rupiah($data->credit) }}
                                                            @else
                                                                <span class="text-danger fw-bold">Debit : </span>
                                                                {{ rupiah($data->debit) }}
                                                            @endif
                                                        </div>
                                                        <div class="">
                                                            <span class="badge rounded-pill border border-warning text-warning">{{$data->status == 'proses' ? 'PROSES' : ''}}</span>
                                                            @if ($data->status == 'process')
                                                            @endif
                                                        </div>
                                                    </div>
                                                    {{ $data->description }}
                                                    <p class="text-grey">Date : {{ $data->created_at }}</p>

                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header fs-5 fw-bold">Produk Katalog</div>
                            <div class="card-body">
                                <div class="row row-cols-1 row-cols-md-3 g-4 ">
                                    @foreach ($products as $product)
                                        <div class="col-md-4 col-lg-4 col-sm-6 col-12 ">
                                            <form action="{{ route('addtoCart') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="price" value="{{ $product->price }}">

                                                <div class="product-card shadow-sm">
                                                    <img class="product-card-img-top img-cover" src="{{ $product->photo }}" alt="Bootstrap Gallery" height="120" style="object-fit: cover;">
                                                    <div class="product-card-body">
                                                        <h5 class="product-title">{{ $product->name }}</h5>
                                                        <div class="product-price">
                                                            <div class="actucal">{{ rupiah($product->price) }}</div>

                                                        </div>
                                                        <div class="product-description">
                                                            <div class="off-price">Stock: {{ $product->stock }}</div>
                                                            {{ $product->description }}
                                                        </div>
                                                        <div class="product-actions">
                                                            <div class="row">
                                                                <div class="col d-flex justify-content-start">
                                                                    <div>
                                                                        <input class="form-control" type="number"name="quantity" value="1" min="1" id="">
                                                                    </div>
                                                                </div>
                                                                <div class="col d-flex justify-content-end">
                                                                    <button type="submit" class="btn btn-outline-primary" {{ $product->stock <= 0 ? 'disabled' : '' }}><i class="bi bi-basket2"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            @endif

            @if (Auth::user()->role == 'kantin')
                <div class="card">
                    <div class="card-header fs-5 fw-bold d-flex justify-content-between align-items-center ">                
                        <div>
                            Produk Katalog
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="bi bi-plus"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Produk</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- <input type="number" min="10000" class="form-control" value="10000" name="credit"> --}}
                                            <div class="container">
                                                <div class="row justify-content-center">                                                                                                       
                                                    <div>
                                                        {{-- TAMBAH PRODUK --}}
                                                        <div class="card">
                                                            <div class="card-header">
                                                            Add Menu
                                                            </div>
                                                            <div class="card-body">
                                                                <form action="{{route('product.store')}}" method="POST" enctype="">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="mb-3">
                                                                                <label>Name</label>
                                                                                <input type="text" name="name" class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="mb-3">
                                                                                <label>Price</label>
                                                                                <input type="number" name="price" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <div class="mb-3">
                                                                                <label>Stock</label>
                                                                                <input type="number" name="stock" class="form-control">
                                                                            </div>
                                                                        </div>                                                                              
                                                                        <div class="col-8">
                                                                            <div class="mb-3">
                                                                                <label>Category</label>
                                                                                <select name="category_id" id="" class="form-select">
                                                                                    <option value="">-- Pilih Opsi --</option>
                                                                                    @foreach ($categories as $category)
                                                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>                          
                                                                    <div class="mb-3">
                                                                        <label>Photo</label>
                                                                        <input type="text" name="photo" class="form-control">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Description</label>
                                                                        <textarea name="description" class="form-control" ></textarea>
                                                                    </div>                                                                
                                                                </div>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>                                                      
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Description</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>

                            @foreach ($products as $key => $product)
                            <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                            <input type="hidden" value="{{ $product->id }}" name="product_id">
                            <input type="hidden" value="{{ $product->price }}" name="price">
                            <tbody>
                              <tr>
                                <th scope="row">{{$key += 1}}</th>
                                <td>{{$product->name}}</td>
                                <td>{{$product->price}}</td>
                                <td>{{$product->stock}}</td>
                                <td><img src="{{ $product->photo }}" style="width: 70px"></td>
                                <td>{{$product->description}}</td>
                                <td class="p-auto d-flex justify-content-roundly">
                                    <!-- Button trigger modal -->
                                    <div class="row">    
                                        {{-- Edit --}}
                                        <div class="col">
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit-{{$product->id}}" >
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <form action="{{ route('product.update', $product) }}" method="POST">
                                                @csrf
                                                @method('put')
                                                <div class="modal fade" id="edit-{{$product->id}}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Produk</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{-- <input type="number" min="10000" class="form-control" value="10000" name="credit"> --}}
                                                                <div class="container">
                                                                    <div class="row justify-content-center">                                                                                                       
                                                                        <div>
                                                                            <div class="card">
                                                                                <div class="card-header">
                                                                                    Edit Product
                                                                                </div>
                                                                                <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col">
                                                                                                <div class="mb-3">
                                                                                                    <label>Name</label>
                                                                                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" >
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col">
                                                                                                <div class="mb-3">
                                                                                                    <label>Price</label>
                                                                                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-2">
                                                                                                <div class="mb-3">
                                                                                                    <label>Stock</label>
                                                                                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
                                                                                                </div>
                                                                                            </div>                                                                                                 
                                                                                            <div class="col-8">
                                                                                                <div class="mb-3">
                                                                                                    <label>Category</label>
                                                                                                    <select name="category_id" id="" class="form-select">
                                                                                                        <option value="{{ $product->category_id }}">{{ $product->category->name }}</option>
                                                                                                        @foreach ($categories as $category)
                                                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>                          
                                                                                        <div class="mb-3">
                                                                                            <label>Photo</label>
                                                                                            <input type="text" name="photo" class="form-control" value="{{ $product->photo }}" >
                                                                                        </div>
                                                                                        <div class="mb-3">
                                                                                            <label>Description</label>
                                                                                            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                                                                                        </div>                                                                
                                                                                    </div>
                                                                                </div>
                                                                            </div>                                                   
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" >Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            
                                        </div>
                                        {{-- Hapus --}}
                                        <div class="col d-flex justify-content-center">
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-{{ $product->id }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>                                            
                                            <!-- Modal -->                                                                                        
                                            <div class="modal fade" id="delete-{{$product->id}}" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteLabel">Delete</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>                
                                                    <form action="{{ route('product.destroy', $product->id )}}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <div class="modal-body text-start">Apakah anda yakin ingin menghapus {{ $product->name }} </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                            <button type="submit" class="btn btn-primary">Ya</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </td>
                              </tr>
                            </tbody>
                            @endforeach
                          </table>
                    </div>
                    
                </div>
                <div class="col">
                    <div class="card mt-1">
                        <div class="card-header fw-bold fs-5">
                            History Transaction
                        </div>
                        <div class="card-body">
                            <ul class="list-group border-0">   
                                @foreach ($transactionAll as $key => $transaction)    
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col fw-bold">
                                                    {{ $transaction[0]->order_id }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col text-secondary" style="font-size: 12px">
                                                    {{ $transaction[0]->created_at }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col d-flex justify-content-end align-items-center">
                                            <a href="{{ route('download', ['order_id' => $transaction[0]->order_id]) }}" class="btn btn-primary">
                                                <i class="bi bi-file-earmark-arrow-down"></i>
                                            </a>
                                        </div>
                                    </div>                                 
                                @endforeach                                         
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->role == 'bank')
                    <div class="">

                        <div class="row">
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-details">
                                        <h3 class="text-green"> {{ rupiah($saldo) }}</h3>
                                        <p>Balance</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-details">
                                        <h3 class="text-red">{{ $creditAll }}</h3>
                                        <p>Credit</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-details">
                                        <h3 class="text-blue">{{ $debitAll }}</h3>
                                        <p>Debit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-xxl-7  col-sm-12 col-12">

                                <div class="card bg-white shadow-sm border-0 mb-4">
                                    <div class="card-header border-0 fs-3 fw-bold">
                                        Request
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">

                                                @foreach ($request_topup as $request)
                                                    <form action="{{ route('acceptRequest') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="wallet_id" value="{{ $request->id }}">
                                                        <div class="card bg-white shadow-sm border-0 mb-3">
                                                            <div class="card-header border-0">
                                                                {{ $request->user->name }}
                                                            </div>
                                                            <div class="card-body d-flex justify-content-between">

                                                                <div class="col my-auto">
                                                                    @if ($request->credit)
                                                                        <span class="text-green fw-bold">Top Up :</span> {{ rupiah($request->credit) }}
                                                                    @elseif ($request->debit)
                                                                    <span class="text-red fw-bold">Withdraw :</span> {{ rupiah($request->debit) }}
                                                                    @endif
                                                                    <div class="text-secondary">
                                                                        <p>{{ $request->created_at }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col text-end">
                                                                    <button type="submit" class="btn btn-primary">Accept Request</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xxl-5  col-sm-12">
                                <div class="card bg-white shadow-sm border-0">
                                    <div class="card-header border-0 fs-5 fw-bold">
                                        History Transaction
                                    </div>

                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach ($mutasi as $data)
                                                <li class="list-group-item">
                                                    <div class="d-flex  justify-content-between align-items-center">
                                                        <div>
                                                            @if ($data->credit)
                                                                <span class="text-success fw-bold">Credit : </span>
                                                                {{ rupiah($data->credit) }}
                                                            @else
                                                                <span class="text-danger fw-bold">Debit : </span>
                                                                {{ rupiah($data->debit) }}
                                                            @endif

                                                        </div>

                                                    </div>
                                                    Name : {{ $data->user->name }}
                                                    <p class="text-grey">{{ $data->description }}</p>
                                                    <p class="text-grey">Date : {{ $data->created_at }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Row end -->
                    </div>
            @endif
        </div>
    </div>
</div>
@endsection
