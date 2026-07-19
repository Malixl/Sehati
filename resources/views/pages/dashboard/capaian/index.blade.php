@extends('layouts.dashboard')

@section('title', 'Hasil Capaian PTM')

@section('content')

    <x-dashboard.page-header title="Hasil Capaian Program PTM"
        subtitle="Laporan rekapitulasi capaian Usia Produktif, Hipertensi, dan Diabetes Melitus per Desa."
        :breadcrumb="['Hasil Capaian' => null]">
        <x-slot name="action">
            <a href="{{ route('dashboard.capaian.export', request()->all()) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
        </x-slot>
    </x-dashboard.page-header>

    <div class="flex flex-wrap items-center justify-end mb-4 gap-3">
        <form id="capaianForm" action="{{ route('dashboard.capaian.index') }}" method="GET"
            class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            
            <input type="hidden" name="period_id" id="period_id_input" value="{{ $selectedPeriodId }}">
            
            <button id="dropdownPeriodButton" data-dropdown-toggle="dropdownPeriod" class="inline-flex items-center justify-between bg-white border border-gray-300 text-gray-900 text-sm rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 p-2.5 shadow-sm min-w-[200px] sm:w-[260px] transition-all duration-300 hover:border-blue-400" type="button">
              <span class="truncate pr-3">
                @php
                    $selectedPeriod = $periods->firstWhere('id', $selectedPeriodId);
                @endphp
                {{ $selectedPeriod ? $selectedPeriod->name . ($selectedPeriod->is_active ? ' (Aktif)' : '') : 'Semua Waktu (Tanpa Periode)' }}
              </span>
              <svg class="w-2.5 h-2.5 ms-2.5 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
              </svg>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdownPeriod" class="z-10 hidden bg-white rounded-lg shadow-lg w-[260px] border border-gray-200">
                <div class="p-3">
                  <label for="input-period-search" class="sr-only">Cari Periode</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                      <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                      </svg>
                    </div>
                    <input type="text" id="input-period-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama periode..." onkeyup="filterPeriodList()">
                  </div>
                </div>
                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700" aria-labelledby="dropdownPeriodButton" id="period-list">
                  <li>
                    <button type="button" onclick="document.getElementById('period_id_input').value=''; document.getElementById('capaianForm').submit();" class="flex items-center w-full text-left ps-2 p-2 rounded hover:bg-gray-100 {{ !$selectedPeriodId ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                      Semua Waktu (Tanpa Periode)
                    </button>
                  </li>
                  @foreach($periods as $period)
                  <li class="period-item">
                    <button type="button" onclick="document.getElementById('period_id_input').value='{{ $period->id }}'; document.getElementById('capaianForm').submit();" class="flex items-center justify-between w-full text-left ps-2 p-2 rounded hover:bg-gray-100 {{ $selectedPeriodId == $period->id ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                      <span class="truncate period-name">{{ $period->name }}</span>
                      @if($period->is_active)
                         <span class="bg-blue-100 text-blue-800 text-[10px] font-semibold px-2 py-0.5 rounded border border-blue-400 ml-2">Aktif</span>
                      @endif
                    </button>
                  </li>
                  @endforeach
                </ul>
            </div>

            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm transition-all duration-300 hover:border-blue-400"
                    placeholder="Cari Desa..." onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-700 border-collapse">
                <thead class="text-xs text-gray-900 uppercase bg-gray-100 border-b-2 border-gray-300">
                    <tr>
                        <th rowspan="3" class="px-3 py-3 border border-gray-300 align-middle">No</th>
                        <th rowspan="3"
                            class="px-4 py-3 border border-gray-300 align-middle text-left whitespace-nowrap min-w-[150px]">
                            NAMA DESA</th>
                        <th colspan="9" class="px-4 py-2 border border-gray-300 text-center">HASIL CAPAIAN PERDESA</th>
                        <th rowspan="3" class="px-2 py-3 border border-gray-300 align-middle">AKSI</th>
                        <th rowspan="3" class="px-2 py-3 border border-gray-300 align-middle">KET</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="px-4 py-2 border border-gray-300 text-center">USIA PRODUKTIF</th>
                        <th colspan="3" class="px-4 py-2 border border-gray-300 text-center bg-red-50">HIPERTENSI</th>
                        <th colspan="3" class="px-4 py-2 border border-gray-300 text-center bg-yellow-50">DIABETES MELITUS
                        </th>
                    </tr>
                    <tr>
                        <th class="px-3 py-2 border border-gray-300">SASARAN</th>
                        <th class="px-3 py-2 border border-gray-300">CAPAIAN</th>
                        <th class="px-2 py-2 border border-gray-300">%</th>
                        
                        <th class="px-3 py-2 border border-gray-300 bg-red-50">SASARAN</th>
                        <th class="px-3 py-2 border border-gray-300 bg-red-50">CAPAIAN</th>
                        <th class="px-2 py-2 border border-gray-300 bg-red-50">%</th>
                        
                        <th class="px-3 py-2 border border-gray-300 bg-yellow-50">SASARAN</th>
                        <th class="px-3 py-2 border border-gray-300 bg-yellow-50">CAPAIAN</th>
                        <th class="px-2 py-2 border border-gray-300 bg-yellow-50">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumSasaranUsia = 0;
                        $sumSasaranHt = 0;
                        $sumSasaranDm = 0;
                        $sumCapaianUsia = 0;
                        $sumCapaianHt = 0;
                        $sumCapaianDm = 0;
                    @endphp
                    @forelse($capaianList as $index => $row)
                        @php
                            $sumSasaranUsia += $row->target_usia_produktif;
                            $sumSasaranHt += $row->target_ht;
                            $sumSasaranDm += $row->target_dm;
                            $sumCapaianUsia += $row->screened_count;
                            $sumCapaianHt += $row->ht_count;
                            $sumCapaianDm += $row->dm_count;
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-white hover:shadow-md transition-all duration-200">
                            <td class="px-3 py-2 border border-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border border-gray-200 font-medium text-gray-900 text-left">{{ $row->name }}
                            </td>

                            {{-- Usia Produktif --}}
                            <td class="px-3 py-2 border border-gray-200 font-semibold">{{ $row->target_usia_produktif }}</td>
                            <td class="px-3 py-2 border border-gray-200">{{ $row->screened_count }}</td>
                            <td class="px-2 py-2 border border-gray-200 font-medium">{{ $row->screened_pct }}%</td>

                            {{-- HT --}}
                            <td class="px-3 py-2 border border-gray-200 font-semibold bg-red-50/30 text-red-700">{{ $row->target_ht }}</td>
                            <td class="px-3 py-2 border border-gray-200 bg-red-50/30">{{ $row->ht_count }}</td>
                            <td class="px-2 py-2 border border-gray-200 font-medium bg-red-50/30">{{ $row->ht_pct }}%</td>

                            {{-- DM --}}
                            <td class="px-3 py-2 border border-gray-200 font-semibold bg-yellow-50/30 text-yellow-700">{{ $row->target_dm }}</td>
                            <td class="px-3 py-2 border border-gray-200 bg-yellow-50/30">{{ $row->dm_count }}</td>
                            <td class="px-2 py-2 border border-gray-200 font-medium bg-yellow-50/30">{{ $row->dm_pct }}%</td>

                            <td class="px-2 py-2 border border-gray-200">
                                <button type="button"
                                    onclick="openTargetModal({{ $row->id }}, '{{ $row->name }}', {{ $row->target_usia_produktif }}, {{ $row->target_ht }}, {{ $row->target_dm }})"
                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-[11px] px-2.5 py-1.5 text-center inline-flex items-center">
                                    Atur Target
                                </button>
                            </td>
                            <td class="px-2 py-2 border border-gray-200"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data desa ditemukan.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($capaianList) > 0)
                        {{-- Row JUMLAH --}}
                        <tr class="font-bold bg-gray-100 border-t-2 border-gray-300">
                            <td colspan="2" class="px-4 py-3 border border-gray-300">JUMLAH</td>
                            
                            {{-- Usia Produktif --}}
                            <td class="px-3 py-3 border border-gray-300 text-gray-900 font-bold">{{ $sumSasaranUsia }}</td>
                            <td class="px-3 py-3 border border-gray-300 text-gray-900">{{ $sumCapaianUsia }}</td>
                            <td class="px-2 py-3 border border-gray-300">
                                {{ $sumSasaranUsia > 0 ? round(($sumCapaianUsia / $sumSasaranUsia) * 100) : 0 }}%</td>

                            {{-- HT --}}
                            <td class="px-3 py-3 border border-gray-300 bg-red-100 font-bold">{{ $sumSasaranHt }}</td>
                            <td class="px-3 py-3 border border-gray-300 bg-red-100">{{ $sumCapaianHt }}</td>
                            <td class="px-2 py-3 border border-gray-300 bg-red-100">
                                {{ $sumSasaranHt > 0 ? round(($sumCapaianHt / $sumSasaranHt) * 100) : 0 }}%</td>

                            {{-- DM --}}
                            <td class="px-3 py-3 border border-gray-300 bg-yellow-100 font-bold">{{ $sumSasaranDm }}</td>
                            <td class="px-3 py-3 border border-gray-300 bg-yellow-100">{{ $sumCapaianDm }}</td>
                            <td class="px-2 py-3 border border-gray-300 bg-yellow-100">
                                {{ $sumSasaranDm > 0 ? round(($sumCapaianDm / $sumSasaranDm) * 100) : 0 }}%</td>
                                
                            <td colspan="2" class="px-2 py-3 border border-gray-300"></td>
                        </tr>

                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Target Modal --}}
    <div id="targetModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto bg-gray-900/50">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Atur Target PTM Desa <span id="modalDesaName" class="text-blue-600"></span>
                    </h3>
                    <button type="button" onclick="closeTargetModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <form id="targetForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="target_usia_produktif" class="block mb-2 text-sm font-medium text-gray-900">Target
                                Usia Produktif</label>
                            <input type="number" name="target_usia_produktif" id="target_usia_produktif"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="target_ht" class="block mb-2 text-sm font-medium text-gray-900">Target Hipertensi
                                (Estimasi Kasus HT)</label>
                            <input type="number" name="target_ht" id="target_ht"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="target_dm" class="block mb-2 text-sm font-medium text-gray-900">Target Diabetes
                                Melitus (Estimasi Kasus DM)</label>
                            <input type="number" name="target_dm" id="target_dm"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5"
                                required>
                        </div>
                        <div class="flex justify-end pt-4 border-t">
                            <button type="button" onclick="closeTargetModal()"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 mr-3">Batal</button>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan
                                Target</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTargetModal(id, name, usia, ht, dm) {
            document.getElementById('modalDesaName').innerText = name;
            document.getElementById('target_usia_produktif').value = usia;
            document.getElementById('target_ht').value = ht;
            document.getElementById('target_dm').value = dm;

            // Set form action
            document.getElementById('targetForm').action = `/dashboard/capaian/village/${id}/target`;

            document.getElementById('targetModal').classList.remove('hidden');
            document.getElementById('targetModal').classList.add('flex');
        }

        function closeTargetModal() {
            document.getElementById('targetModal').classList.add('hidden');
            document.getElementById('targetModal').classList.remove('flex');
        }
    </script>

    <script>
        function filterPeriodList() {
            var input, filter, ul, li, name, i, txtValue;
            input = document.getElementById('input-period-search');
            filter = input.value.toUpperCase();
            ul = document.getElementById('period-list');
            li = ul.getElementsByClassName('period-item');
            for (i = 0; i < li.length; i++) {
                name = li[i].querySelector('.period-name');
                txtValue = name.textContent || name.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endsection