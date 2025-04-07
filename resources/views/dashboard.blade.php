<!-- resources/views/kidney/form.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kidney Health Screening</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-slate-100 to-blue-100 min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-500 text-white text-center py-6">
            <h1 class="text-2xl font-bold uppercase tracking-wide">Lorem Ipsum</h1>
        </div>

        <!-- Form -->
        <form id="kidney-screening-form" method="POST" action="{{ route('dashboard') }}" class="p-6 md:p-10">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    @foreach ([
                        'age' => 'Usia',
                        'suhu_tubuh' => 'Suhu Tubuh',
                        'tekanan_darah' => 'Tekanan Darah',
                        'asam_urat' => 'Asam Urat',
                        'kadar_urine' => 'Kadar Urine',
                        'warna_urine' => 'Warna Urine',
                        'konsumsi_air_putih' => 'Konsumsi Air Putih'
                    ] as $id => $label)
                        <div>
                            <label for="{{ $id }}" class="block font-semibold text-gray-700 mb-1">{{ $label }}</label>
                            <input type="{{ $id === 'age' ? 'number' : 'text' }}" id="{{ $id }}" name="{{ $id }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    @endforeach
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    @foreach ([
                        'nyeri_pinggang' => 'Nyeri Pinggang',
                        'sering_berkemih' => 'Sering Berkemih',
                        'mudah_lelah' => 'Mudah Lelah',
                        'mual_muntah' => 'Mual dan Muntah',
                        'riwayat_penyakit_ginjal' => 'Riwayat Penyakit Ginjal di Keluarga',
                        'riwayat_penyakit_hipertensi' => 'Riwayat Penyakit Hipertensi',
                        'riwayat_penyakit_diabetes' => 'Riwayat Penyakit Diabetes'
                    ] as $name => $label)
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">{{ $label }}</label>
                            <div class="flex space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="{{ $name }}" value="ya" class="text-blue-500">
                                    <span class="ml-2">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="{{ $name }}" value="tidak" class="text-blue-500">
                                    <span class="ml-2">Tidak</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8">
                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-md shadow transition">
                    SUBMIT
                </button>
            </div>
        </form>
    </div>
</body>
</html>
