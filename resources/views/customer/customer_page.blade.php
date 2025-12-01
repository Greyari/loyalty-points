@extends('layouts.nav')

@section('title', 'Customer Page')
@section('page_title', 'Customer')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Customer Data</h2>
    <p class="text-lg font-light text-gray-500 font-poppins">Manage your customer data.</p>
</div>

@php
$customers = [
[
'id'=>1,
'name'=>'Alice Johnson',
'company'=>'PT. Alpha',
'email'=>'alice@example.com',
'phone'=>'081234567890',
'points'=>2000,
'last_transaction'=>'2025-11-28'
],
[
'id'=>2,
'name'=>'Bob Smith',
'company'=>'PT. Beta',
'email'=>'bob@example.com',
'phone'=>'082233445566',
'points'=>1500,
'last_transaction'=>'2025-11-25'
],
[
'id'=>3,
'name'=>'Charlie Lee',
'company'=>'PT. Gamma',
'email'=>'charlie@example.com',
'phone'=>'083344556677',
'points'=>3000,
'last_transaction'=>'2025-11-30'
],
[
'id'=>4,
'name'=>'Diana Prince',
'company'=>'PT. Delta',
'email'=>'diana@example.com',
'phone'=>'084455667788',
'points'=>1800,
'last_transaction'=>'2025-11-27'
],
[
'id'=>3,
'name'=>'Charlie Lee',
'company'=>'PT. Gamma',
'email'=>'charlie@example.com',
'phone'=>'083344556677',
'points'=>3000,
'last_transaction'=>'2025-11-30'
],
[
'id'=>3,
'name'=>'Charlie Lee',
'company'=>'PT. Gamma',
'email'=>'charlie@example.com',
'phone'=>'083344556677',
'points'=>3000,
'last_transaction'=>'2025-11-30'
],
];
@endphp

<x-data-tables
    :headers="['Customer', 'Contact', 'Points', 'Rank', 'Last Transaction']"
    :rows="array_map(function($customer, $index){
        $rankColors = ['bg-yellow-400', 'bg-gray-400', 'bg-orange-500'];
        $rankColor = $rankColors[$index] ?? 'bg-gray-300';
        return [
            'id' => $customer['id'],
            'customer' => '<div class=\'flex items-center gap-3\'><div class=\'w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 font-semibold uppercase\'>'.collect(explode(' ', $customer['name']))->map(fn($n)=>substr($n,0,1))->join('').'</div><div class=\'flex flex-col\'><span class=\'font-medium text-gray-800 font-poppins\'>'.$customer['name'].'</span><span class=\'text-gray-500 text-sm font-poppins\'>'.$customer['company'].'</span></div></div>',
            'contact' => '<div class=\'flex flex-col text-gray-700 font-poppins\'><span>'.$customer['email'].'</span><span class=\'text-sm text-gray-500\'>'.$customer['phone'].'</span></div>',
            'points' => '<div class=\'flex items-center gap-2 text-gray-700 font-poppins\'><svg class=\'w-5 h-5 text-yellow-500\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.561-.955L10 0l2.949 5.955 6.561.955-4.755 4.635 1.123 6.545z\' /></svg> '.$customer['points'].'</div>',
            'rank' => '<div class=\'w-6 h-6 rounded-full '.$rankColor.' flex items-center justify-center text-white font-semibold text-sm\'>'.($index+1).'</div>',
            'last_transaction' => \Carbon\Carbon::parse($customer['last_transaction'])->format('d M Y')
        ];
    }, $customers, array_keys($customers))"
    onAdd="true"
    onEdit="true"
    onDelete="true" />
@endsection