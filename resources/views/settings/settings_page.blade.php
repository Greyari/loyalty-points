{{-- @extends('layouts.nav')

@section('title', 'Aturan Poin')
@section('page_title', 'Aturan Poin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-[#0F1724] text-4xl font-semibold font-poppins mb-2">Aturan Poin</h1>
            <p class="text-[#475569] text-lg font-light font-poppins">Konfigurasi sistem poin loyalty program</p>
        </div>

    </div>

    <form id="pointsRulesForm" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Basic Conversion -->
            <div class="bg-white border border-[#E6EEF8] rounded-lg shadow-sm">
                <div class="p-6 border-b border-[#E6EEF8]">
                    <h3 class="text-[#0F1724] text-lg font-semibold flex items-center gap-2 font-poppins">
                        <svg class="w-5 h-5 text-[#0B69FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Konversi Poin Dasar
                    </h3>
                    <p class="text-sm text-[#475569] mt-1 font-poppins">Tentukan nilai rupiah untuk mendapatkan 1 poin</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label for="conversionRate" class="block text-sm font-medium text-[#0F1724]">1 Poin = Rp</label>
                        <input
                            type="number"
                            id="conversionRate"
                            name="conversion_rate"
                            value="10000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            placeholder="10000"
                            oninput="updateSimulation()">
                        <p class="text-sm text-[#475569]">
                            Contoh: Rp <span id="conversionExample">10.000</span> = 1 poin
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] rounded-lg p-4 space-y-2">
                        <div class="text-sm text-[#0F1724] font-medium ">Simulasi:</div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#475569]">Transaksi Rp 100.000:</span>
                            <span class="text-[#0B69FF] font-medium" id="sim100k">10 poin</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#475569]">Transaksi Rp 1.000.000:</span>
                            <span class="text-[#0B69FF] font-medium" id="sim1m">100 poin</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#475569]">Transaksi Rp 10.000.000:</span>
                            <span class="text-[#0B69FF] font-medium" id="sim10m">1000 poin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bonus Points -->
            <div class="bg-white border border-[#E6EEF8] rounded-lg shadow-sm">
                <div class="p-6 border-b border-[#E6EEF8]">
                    <h3 class="text-[#0F1724] text-lg font-semibold flex items-center gap-2 font-poppins">
                        <svg class="w-5 h-5 text-[#FFD166]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        Poin Bonus
                    </h3>
                    <p class="text-sm text-[#475569] mt-1 font-poppins">Berikan poin bonus untuk aktivitas tertentu</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="space-y-0.5">
                            <label class="block text-sm font-medium text-[#0F1724]">Aktifkan Poin Bonus</label>
                            <p class="text-sm text-[#475569]">Berikan poin untuk pembelian pertama</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="bonusEnabled" name="bonus_enabled" class="sr-only peer" checked onchange="toggleBonus()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0B69FF]"></div>
                        </label>
                    </div>

                    <div id="bonusSection" class="space-y-2 pl-4 border-l-2 border-[#0B69FF]">
                        <label for="bonusFirstPurchase" class="block text-sm font-medium text-[#0F1724]">Poin Pembelian Pertama</label>
                        <input
                            type="number"
                            id="bonusFirstPurchase"
                            name="bonus_first_purchase"
                            value="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            placeholder="100"
                            oninput="updateBonusText()">
                        <p class="text-sm text-[#475569]">
                            Pelanggan baru mendapat +<span id="bonusText">100</span> poin
                        </p>
                    </div>
                </div>
            </div>

            <!-- Point Expiration -->
            <div class="bg-white border border-[#E6EEF8] rounded-lg shadow-sm">
                <div class="p-6 border-b border-[#E6EEF8]">
                    <h3 class="text-[#0F1724] text-lg font-semibold flex items-center gap-2 font-poppins">
                        <svg class="w-5 h-5 text-[#E03131]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Masa Berlaku Poin
                    </h3>
                    <p class="text-sm text-[#475569] mt-1 font-poppins">Tentukan durasi poin sebelum kadaluarsa</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="space-y-0.5">
                            <label class="block text-sm font-medium text-[#0F1724]">Aktifkan Kadaluarsa</label>
                            <p class="text-sm text-[#475569]">Poin akan hangus setelah jangka waktu tertentu</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="expirationEnabled" name="expiration_enabled" class="sr-only peer" checked onchange="toggleExpiration()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0B69FF]"></div>
                        </label>
                    </div>

                    <div id="expirationSection" class="space-y-2 pl-4 border-l-2 border-[#E03131]">
                        <label for="expirationDays" class="block text-sm font-medium text-[#0F1724]">Masa Berlaku (Hari)</label>
                        <input
                            type="number"
                            id="expirationDays"
                            name="expiration_days"
                            value="365"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            placeholder="365"
                            oninput="updateExpirationText()">
                        <p class="text-sm text-[#475569]">
                            Poin akan kadaluarsa setelah <span id="expirationText">365</span> hari
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tier Multipliers -->
            <div class="bg-white border border-[#E6EEF8] rounded-lg shadow-sm">
                <div class="p-6 border-b border-[#E6EEF8]">
                    <h3 class="text-[#0F1724] text-lg font-semibold flex items-center gap-2 font-poppins">
                        <svg class="w-5 h-5 text-[#18A058]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Multiplier Tier
                    </h3>
                    <p class="text-sm text-[#475569] mt-1 font-poppins">Bonus poin untuk tier member premium</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-3">
                        <!-- Gold Tier -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-linear-to-br from-[#FFD166] to-[#FFA726] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-[#0F1724]">Gold Member</label>
                                <p class="text-xs text-[#475569]">≥ <span id="goldThresholdLabel">100.000</span> poin</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    id="goldMultiplier"
                                    name="gold_multiplier"
                                    step="0.05"
                                    value="1.25"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                                    oninput="updateTierCalculation()">
                                <span class="text-sm text-[#475569]"></span>
                            </div>
                        </div>

                        <!-- Silver Tier -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-linear-to-br from-[#C0C8D6] to-[#94A3B8] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-[#0F1724]">Silver Member</label>
                                <p class="text-xs text-[#475569]">≥ <span id="silverThresholdLabel">50.000</span> poin</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    id="silverMultiplier"
                                    name="silver_multiplier"
                                    step="0.05"
                                    value="1.15"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                                    oninput="updateTierCalculation()">
                                <span class="text-sm text-[#475569]"></span>
                            </div>
                        </div>

                        <!-- Bronze Tier -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-linear-to-br from-[#D99B6C] to-[#B87333] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-[#0F1724]">Bronze Member</label>
                                <p class="text-xs text-[#475569]">≥ <span id="bronzeThresholdLabel">25.000</span> poin</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    id="bronzeMultiplier"
                                    name="bronze_multiplier"
                                    step="0.05"
                                    value="1.10"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                                    oninput="updateTierCalculation()">
                                <span class="text-sm text-[#475569]"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#F8FAFC] rounded-lg p-4">
                        <div class="text-sm text-[#0F1724] font-medium mb-2">Contoh Perhitungan:</div>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#475569]">Regular member (Rp 1jt):</span>
                                <span class="text-[#0B69FF] font-medium" id="regularCalc">100 poin</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#475569]">Gold member (Rp 1jt):</span>
                                <span class="text-[#FFD166] font-medium" id="goldCalc">125 poin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tier Thresholds Configuration -->
        <div class="bg-white border border-[#E6EEF8] rounded-lg shadow-sm">
            <div class="p-6 border-b border-[#E6EEF8]">
                <h3 class="text-[#0F1724] text-lg font-semibold flex items-center gap-2 font-poppins">
                    <svg class="w-5 h-5 text-[#475569]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Batas Tier Member
                </h3>
                <p class="text-sm text-[#475569] mt-1 font-poppins">Tentukan jumlah poin minimum untuk setiap tier</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label for="goldThreshold" class="block text-sm font-medium text-[#0F1724]">Gold Tier (Poin Minimum)</label>
                        <input
                            type="number"
                            id="goldThreshold"
                            name="gold_threshold"
                            value="100000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            oninput="updateThresholdLabels()">
                    </div>
                    <div class="space-y-2">
                        <label for="silverThreshold" class="block text-sm font-medium text-[#0F1724]">Silver Tier (Poin Minimum)</label>
                        <input
                            type="number"
                            id="silverThreshold"
                            name="silver_threshold"
                            value="50000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            oninput="updateThresholdLabels()">
                    </div>
                    <div class="space-y-2">
                        <label for="bronzeThreshold" class="block text-sm font-medium text-[#0F1724]">Bronze Tier (Poin Minimum)</label>
                        <input
                            type="number"
                            id="bronzeThreshold"
                            name="bronze_threshold"
                            value="25000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0B69FF] focus:border-transparent"
                            oninput="updateThresholdLabels()">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="button" onclick="saveRules()" class="inline-flex items-center px-4 py-2 bg-[#0B69FF] hover:bg-[#0E4FD8] text-white rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>
@endsection --}}
