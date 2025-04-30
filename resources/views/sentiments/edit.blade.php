<!-- resources/views/sentiments/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Sentimen')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">Edit Data Sentimen</h1>
        <p class="text-white/80 max-w-2xl mx-auto">
            Ubah data sentimen yang telah dianalisis
        </p>
    </div>

    <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl max-w-2xl mx-auto">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6">
            <h2 class="text-xl font-bold">Edit Sentimen</h2>
            <p class="text-white/80">Ubah data sentimen sesuai kebutuhan</p>
        </div>
        <div class="p-6">
            <form action="{{ route('sentiments.update', $sentiment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-1">Teks Feedback</label>
                    <textarea id="text" name="text" rows="4" class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="Masukkan teks feedback">{{ old('text', $sentiment->text) }}</textarea>
                    @error('text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="sentiment" class="block text-sm font-medium text-gray-700 mb-1">Jenis Sentimen</label>
                    <select id="sentiment" name="sentiment" class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all">
                        <option value="positive" {{ old('sentiment', $sentiment->sentiment) === 'positive' ? 'selected' : '' }}>Positif</option>
                        <option value="neutral" {{ old('sentiment', $sentiment->sentiment) === 'neutral' ? 'selected' : '' }}>Netral</option>
                        <option value="negative" {{ old('sentiment', $sentiment->sentiment) === 'negative' ? 'selected' : '' }}>Negatif</option>
                    </select>
                    @error('sentiment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="probability" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kepercayaan: <span id="probabilityValue">{{ round($sentiment->probability * 100) }}</span>%</label>
                    <input type="range" id="probabilityRange" min="1" max="100" value="{{ round($sentiment->probability * 100) }}" class="w-full" oninput="updateProbability(this.value)">
                    <input type="hidden" id="probability" name="probability" value="{{ $sentiment->probability }}">
                    @error('probability')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div id="probabilityBar" class="h-2 rounded-full bg-blue-500" style="width: {{ round($sentiment->probability * 100) }}%;"></div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-2">
                    <a href="{{ route('sentiments.history') }}" class="px-4 py-2 border-2 border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-md transition-all duration-300 shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateProbability(value) {
        document.getElementById('probabilityValue').textContent = value;
        document.getElementById('probability').value = value / 100;
        document.getElementById('probabilityBar').style.width = value + '%';
        
        // Update color based on sentiment
        const sentiment = document.getElementById('sentiment').value;
        let color = '';
        
        if (sentiment === 'positive') {
            color = 'bg-green-500';
        } else if (sentiment === 'negative') {
            color = 'bg-red-500';
        } else {
            color = 'bg-yellow-500';
        }
        
        document.getElementById('probabilityBar').className = `h-2 rounded-full ${color}`;
    }
    
    document.getElementById('sentiment').addEventListener('change', function() {
        updateProbability(document.getElementById('probabilityRange').value);
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateProbability(document.getElementById('probabilityRange').value);
    });
</script>
@endpush