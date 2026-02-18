@extends('layouts.admin')

@section('title', 'Edit Program')

@section('content')
<style>
/* Style untuk textarea yang bisa resize */
.resize-textarea {
    resize: vertical;
    min-height: 100px;
    max-height: 300px;
}

/* Style untuk select dengan dropdown panjang */
.select-long {
    height: 100px;
}

/* Badge untuk kode rekening */
.rekening-badge {
    @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium;
}

.rekening-0001 {
    @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.rekening-0003 {
    @apply bg-green-100 text-green-800 border border-green-200;
}

/* Style untuk preview pegawai */
.pegawai-preview {
    @apply p-3 bg-gray-50 border border-gray-200 rounded-lg transition duration-150;
}

.pegawai-preview:hover {
    @apply bg-blue-50 border-blue-300;
}

/* Style untuk informasi timestamp */
.timestamp-info {
    @apply p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Data Program</h2>
            <p class="text-gray-500">Perbarui data program yang sudah ada</p>
        </div>
        <a href="{{ route('program.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Notifikasi -->
@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">Terjadi kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form Edit Program -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('program.update', $program->id_program) }}" method="POST" id="formProgram">
            @csrf
            @method('PUT')
            
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Anda sedang mengedit data program dengan ID: <span class="font-semibold">{{ $program->id_program }}</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Informasi Program -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-clipboard-list text-blue-500 mr-2"></i> Informasi Program
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Program -->
                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-2">
                                Program <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="program" 
                                name="program" 
                                rows="3"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-textarea"
                                placeholder="Masukkan nama program">{{ old('program', $program->program) }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Contoh: Program Pelayanan Penanaman Modal</span>
                                <span id="program-counter">{{ strlen(old('program', $program->program)) }}/200 karakter</span>
                            </div>
                        </div>

                        <!-- Kode Rekening -->
                        <div>
                            <label for="kode_rekening" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Rekening <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="kode_rekening" 
                                name="kode_rekening" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            >
                                <option value="">-- Pilih Kode Rekening --</option>
                                @foreach($rekenings ?? [] as $key => $value)
                                    <option value="{{ $key }}" {{ old('kode_rekening', $program->kode_rekening) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1 text-xs text-gray-500">
                                <span>Pilih salah satu kode rekening</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kegiatan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-tasks text-green-500 mr-2"></i> Informasi Kegiatan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kegiatan -->
                        <div>
                            <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="kegiatan" 
                                name="kegiatan" 
                                rows="4"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-textarea"
                                placeholder="Masukkan nama kegiatan">{{ old('kegiatan', $program->kegiatan) }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Contoh: Pelayanan Perizinan dan Non Perizinan...</span>
                                <span id="kegiatan-counter">{{ strlen(old('kegiatan', $program->kegiatan)) }}/200 karakter</span>
                            </div>
                        </div>

                        <!-- Sub Kegiatan -->
                        <div>
                            <label for="sub_kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="sub_kegiatan" 
                                name="sub_kegiatan" 
                                rows="4"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-textarea"
                                placeholder="Masukkan nama sub kegiatan">{{ old('sub_kegiatan', $program->sub_kegiatan) }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Contoh: Penyediaan Pelayanan Perizinan Berusaha...</span>
                                <span id="subkegiatan-counter">{{ strlen(old('sub_kegiatan', $program->sub_kegiatan)) }}/200 karakter</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pegawai Penanggung Jawab -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-user-tie text-indigo-500 mr-2"></i> Pegawai Penanggung Jawab
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Pilih Pegawai -->
                        <div>
                            <label for="id_pegawai" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Pegawai <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="id_pegawai" 
                                name="id_pegawai" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 select-long"
                            >
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais ?? [] as $pegawai)
                                    <option value="{{ $pegawai->id_pegawai }}" {{ old('id_pegawai', $program->id_pegawai) == $pegawai->id_pegawai ? 'selected' : '' }}>
                                        {{ $pegawai->nama }} - {{ $pegawai->jabatan ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1 text-xs text-gray-500">
                                <span>Pilih pegawai yang bertanggung jawab atas program ini</span>
                            </div>
                        </div>

                <!-- Informasi Timestamp -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <i class="fas fa-history text-gray-500 mr-2"></i> Informasi Sistem
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="timestamp-info">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-plus text-gray-400 mr-2 text-sm"></i>
                                <span class="text-xs font-medium text-gray-500">Dibuat Pada</span>
                            </div>
                            <div class="text-sm text-gray-700">
                                {{ $program->created_at ? $program->created_at->format('d/m/Y H:i:s') : '-' }}
                            </div>
                        </div>
                        
                        <div class="timestamp-info">
                            <div class="flex items-center mb-1">
                                <i class="fas fa-calendar-edit text-gray-400 mr-2 text-sm"></i>
                                <span class="text-xs font-medium text-gray-500">Terakhir Diperbarui</span>
                            </div>
                            <div class="text-sm text-gray-700">
                                {{ $program->updated_at ? $program->updated_at->format('d/m/Y H:i:s') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                <div class="flex space-x-3">
                    <a href="{{ route('program.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                        <i class="fas fa-save mr-2"></i> Update Program
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ========== DATA DARI CONTROLLER ==========
const pegawaiData = @json($pegawaiData ?? []);

// Data rekening untuk preview
const rekeningData = {
    '5.1.02.04.01.0001': {
        'badge': 'rekening-0001',
        'jenis': 'Belanja Barang Operasional'
    },
    '5.1.02.04.01.0003': {
        'badge': 'rekening-0003',
        'jenis': 'Belanja Barang Perlengkapan'
    }
};

// ========== FUNGSI UTAMA ==========
function updateCounter(textareaId, counterId, maxLength) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);
    
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length}/${maxLength} karakter`;
        
        if (length > maxLength) {
            counter.classList.add('text-red-600', 'font-medium');
            this.classList.add('border-red-500');
        } else {
            counter.classList.remove('text-red-600', 'font-medium');
            this.classList.remove('border-red-500');
        }
    });
    
    // Update counter dengan nilai awal
    const initialLength = textarea.value.length;
    counter.textContent = `${initialLength}/${maxLength} karakter`;
}

function updatePegawaiPreview() {
    const select = document.getElementById('id_pegawai');
    const previewContainer = document.getElementById('pegawai-preview');
    
    if (select.value && pegawaiData[select.value]) {
        const pegawai = pegawaiData[select.value];
        
        document.getElementById('preview-initial').textContent = pegawai.initial;
        document.getElementById('preview-nama').textContent = pegawai.nama;
        document.getElementById('preview-nip').textContent = pegawai.nip;
        document.getElementById('preview-jabatan').textContent = pegawai.jabatan;
        
        previewContainer.classList.remove('hidden');
    } else {
        previewContainer.classList.add('hidden');
    }
}

function updateRekeningPreview() {
    const select = document.getElementById('kode_rekening');
    const previewContainer = document.getElementById('rekening-preview');
    
    if (select.value && rekeningData[select.value]) {
        const rekening = rekeningData[select.value];
        
        document.getElementById('preview-kode-rekening').textContent = select.value;
        document.getElementById('preview-badge').textContent = select.value;
        document.getElementById('preview-badge').className = `rekening-badge ${rekening.badge}`;
        document.getElementById('preview-jenis-rekening').textContent = rekening.jenis;
        
        previewContainer.classList.remove('hidden');
    } else {
        previewContainer.classList.add('hidden');
    }
}

// ========== VALIDASI ==========
document.getElementById('formProgram').addEventListener('submit', function(e) {
    const fields = [
        {id: 'program', name: 'Program', max: 200},
        {id: 'kegiatan', name: 'Kegiatan', max: 200},
        {id: 'sub_kegiatan', name: 'Sub Kegiatan', max: 200},
        {id: 'kode_rekening', name: 'Kode Rekening', max: null},
        {id: 'id_pegawai', name: 'Pegawai', max: null}
    ];
    
    for (let field of fields) {
        const element = document.getElementById(field.id);
        
        // Validasi wajib diisi
        if (!element.value.trim()) {
            alert(`${field.name} wajib diisi`);
            e.preventDefault();
            element.focus();
            return false;
        }
        
        // Validasi maksimal karakter
        if (field.max && element.value.length > field.max) {
            alert(`${field.name} maksimal ${field.max} karakter`);
            e.preventDefault();
            element.focus();
            return false;
        }
    }
    
    return true;
});

// ========== INISIALISASI ==========
document.addEventListener('DOMContentLoaded', function() {
    // Setup counters
    updateCounter('program', 'program-counter', 200);
    updateCounter('kegiatan', 'kegiatan-counter', 200);
    updateCounter('sub_kegiatan', 'subkegiatan-counter', 200);
    
    // Event listeners untuk preview
    document.getElementById('id_pegawai').addEventListener('change', updatePegawaiPreview);
    document.getElementById('kode_rekening').addEventListener('change', updateRekeningPreview);
    
    // Auto-resize textareas
    document.querySelectorAll('.resize-textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger resize untuk mengatur tinggi awal
        textarea.dispatchEvent(new Event('input'));
    });
    
    // Auto-focus ke input program
    document.getElementById('program').focus();
});
</script>
@endsection