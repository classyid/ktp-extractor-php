<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekstraksi Data KTP</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
        }
        
        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Loading Overlay -->
    <div id="loading" class="loading" style="display: none;">
        <div class="loading-spinner mb-4"></div>
        <p>Sedang memproses gambar KTP...</p>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-center mb-6">
                    <i class="fas fa-id-card text-4xl text-blue-600 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Ekstraksi Data KTP</h1>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Aplikasi ini memungkinkan Anda untuk mengekstrak data dari KTP Indonesia dengan cepat dan akurat.
                        Cukup unggah gambar KTP dan sistem akan otomatis mengekstrak informasi.
                    </p>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p><?php echo $_SESSION['error']; ?></p>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                </div>
                
                <form id="ktpForm" class="space-y-4" enctype="multipart/form-data">
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center" id="drop-area">
                        <input type="file" id="fileInput" name="fileInput" accept="image/*" class="hidden">
                        <label for="fileInput" class="cursor-pointer">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-3"></i>
                                <p class="text-gray-700 mb-2">
                                    <span class="font-semibold text-blue-600">Klik untuk memilih</span> atau seret gambar KTP ke sini
                                </p>
                                <p class="text-sm text-gray-500">JPG, JPEG, atau PNG</p>
                            </div>
                        </label>
                        <div id="preview" class="mt-4 hidden">
                            <img id="imagePreview" class="max-h-64 mx-auto rounded border border-gray-300" src="#" alt="Preview KTP">
                            <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
                        </div>
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 flex items-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-search mr-2"></i>
                            Ekstrak Data KTP
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Hasil Ekstraksi -->
            <div id="result" class="bg-white rounded-lg shadow-lg p-6 mb-8 hidden">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-list-alt text-green-600 mr-2"></i>
                    Hasil Ekstraksi Data
                </h2>
                
                <div id="alertContainer" class="mb-4"></div>
                
                <div id="ktpData" class="space-y-4">
                    <!-- Data KTP akan ditampilkan di sini -->
                </div>
                
                <div class="flex justify-center mt-6 space-x-4">
                    <button id="copyBtn" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-copy mr-2"></i>
                        Salin Data
                    </button>
                    <button id="downloadBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Unduh JSON
                    </button>
                    <button id="resetBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
        
        <footer class="text-center text-gray-500 text-sm mt-8">
            <p>&copy; <?php echo date('Y'); ?> KTP Extractor. Dibuat dengan <i class="fas fa-heart text-red-500"></i></p>
        </footer>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('ktpForm');
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');
        const result = document.getElementById('result');
        const ktpData = document.getElementById('ktpData');
        const copyBtn = document.getElementById('copyBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const resetBtn = document.getElementById('resetBtn');
        const loading = document.getElementById('loading');
        const dropArea = document.getElementById('drop-area');
        const alertContainer = document.getElementById('alertContainer');
        
        // API endpoint
        const API_URL = 'https://script.google.com/macros/s/[ID-DEPLOY]/exec';
        let extractedData = null;
        
        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('border-blue-500', 'bg-blue-50');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-blue-500', 'bg-blue-50');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        }
        
        // Handle file selection
        fileInput.addEventListener('change', handleFileSelect);
        
        function handleFileSelect() {
            const file = fileInput.files[0];
            
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    showAlert('error', 'Format file tidak valid. Gunakan JPG, JPEG, atau PNG.');
                    resetForm();
                    return;
                }
                
                // Validate file size (max 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    showAlert('error', 'Ukuran file terlalu besar. Maksimum 5MB.');
                    resetForm();
                    return;
                }
                
                // Display preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    preview.classList.remove('hidden');
                    fileName.textContent = file.name;
                    submitBtn.disabled = false;
                };
                reader.readAsDataURL(file);
            }
        }
        
        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const file = fileInput.files[0];
            if (!file) return;
            
            // Show loading
            loading.style.display = 'flex';
            
            try {
                // Read file as base64
                const base64Data = await readFileAsBase64(file);
                
                // Remove data:image/jpeg;base64, prefix
                const base64Clean = base64Data.split(',')[1];
                
                // Prepare data for API
                const formData = new FormData();
                formData.append('action', 'process-ktp');
                formData.append('fileData', base64Clean);
                formData.append('fileName', file.name);
                formData.append('mimeType', file.type);
                
                // Call API
                const response = await fetch(API_URL, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                // Hide loading
                loading.style.display = 'none';
                
                if (data.status === 'success') {
                    displayResult(data);
                } else {
                    showAlert('error', 'Terjadi kesalahan: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                loading.style.display = 'none';
                showAlert('error', 'Terjadi kesalahan saat menghubungi API. Silakan coba lagi.');
            }
        });
        
        // Read file as base64
        function readFileAsBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }
        
        // Display result
        function displayResult(data) {
            // Clear previous data
            ktpData.innerHTML = '';
            extractedData = data;
            
            if (data.data.analysis.parsed.status === 'not_ktp') {
                showAlert('warning', 'Dokumen yang diunggah bukan merupakan KTP.');
                return;
            }
            
            // Get parsed data
            const parsedData = data.data.analysis.parsed;
            
            // Create data table
            const fields = [
                { key: 'nik', label: 'NIK', icon: 'fa-id-card' },
                { key: 'nama', label: 'Nama', icon: 'fa-user' },
                { key: 'tempat_tanggal_lahir', label: 'Tempat/Tanggal Lahir', icon: 'fa-calendar-alt' },
                { key: 'jenis_kelamin', label: 'Jenis Kelamin', icon: 'fa-venus-mars' },
                { key: 'golongan_darah', label: 'Golongan Darah', icon: 'fa-tint' },
                { key: 'alamat', label: 'Alamat', icon: 'fa-home' },
                { key: 'rt_rw', label: 'RT/RW', icon: 'fa-map-marker-alt' },
                { key: 'kel_desa', label: 'Kel/Desa', icon: 'fa-city' },
                { key: 'kecamatan', label: 'Kecamatan', icon: 'fa-map' },
                { key: 'agama', label: 'Agama', icon: 'fa-pray' },
                { key: 'status_perkawinan', label: 'Status Perkawinan', icon: 'fa-ring' },
                { key: 'pekerjaan', label: 'Pekerjaan', icon: 'fa-briefcase' },
                { key: 'kewarganegaraan', label: 'Kewarganegaraan', icon: 'fa-flag' },
                { key: 'berlaku_hingga', label: 'Berlaku Hingga', icon: 'fa-clock' },
                { key: 'dikeluarkan_di', label: 'Dikeluarkan di', icon: 'fa-building' }
            ];
            
            // Create HTML for data items
            fields.forEach(field => {
                if (parsedData[field.key]) {
                    const dataItem = document.createElement('div');
                    dataItem.className = 'bg-gray-50 p-3 rounded-md';
                    dataItem.innerHTML = `
                        <div class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas ${field.icon} text-blue-500 mr-2"></i>
                            ${field.label}
                        </div>
                        <div class="text-lg font-medium">${parsedData[field.key]}</div>
                    `;
                    ktpData.appendChild(dataItem);
                }
            });
            
            // Show result section
            result.classList.remove('hidden');
            showAlert('success', 'Data KTP berhasil diekstrak!');
            
            // Scroll to result
            result.scrollIntoView({ behavior: 'smooth' });
        }
        
        // Show alert
        function showAlert(type, message) {
            let bgColor, textColor, borderColor, icon;
            
            switch (type) {
                case 'success':
                    bgColor = 'bg-green-100';
                    textColor = 'text-green-700';
                    borderColor = 'border-green-500';
                    icon = 'fa-check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-100';
                    textColor = 'text-red-700';
                    borderColor = 'border-red-500';
                    icon = 'fa-exclamation-circle';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-100';
                    textColor = 'text-yellow-700';
                    borderColor = 'border-yellow-500';
                    icon = 'fa-exclamation-triangle';
                    break;
                default:
                    bgColor = 'bg-blue-100';
                    textColor = 'text-blue-700';
                    borderColor = 'border-blue-500';
                    icon = 'fa-info-circle';
            }
            
            alertContainer.innerHTML = `
                <div class="${bgColor} border-l-4 ${borderColor} ${textColor} p-4" role="alert">
                    <div class="flex items-center">
                        <i class="fas ${icon} mr-2"></i>
                        <p>${message}</p>
                    </div>
                </div>
            `;
        }
        
        // Copy data to clipboard
        copyBtn.addEventListener('click', function() {
            if (!extractedData) return;
            
            // Format data for clipboard
            const parsedData = extractedData.data.analysis.parsed;
            let clipboardText = '';
            
            for (const [key, value] of Object.entries(parsedData)) {
                if (key !== 'status') {
                    // Convert snake_case to Title Case
                    const formattedKey = key.split('_').map(word => 
                        word.charAt(0).toUpperCase() + word.slice(1)
                    ).join(' ');
                    
                    clipboardText += `${formattedKey}: ${value}\n`;
                }
            }
            
            // Copy to clipboard
            navigator.clipboard.writeText(clipboardText).then(() => {
                showAlert('success', 'Data berhasil disalin ke clipboard!');
            }).catch(err => {
                console.error('Error copying text: ', err);
                showAlert('error', 'Gagal menyalin data. Silakan coba lagi.');
            });
        });
        
        // Download data as JSON
        downloadBtn.addEventListener('click', function() {
            if (!extractedData) return;
            
            // Format data for download
            const dataStr = JSON.stringify(extractedData.data.analysis.parsed, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
            
            // Create download link
            const fileName = 'ktp-data_' + new Date().toISOString().slice(0, 10) + '.json';
            const downloadLink = document.createElement('a');
            downloadLink.setAttribute('href', dataUri);
            downloadLink.setAttribute('download', fileName);
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
        
        // Reset form
        resetBtn.addEventListener('click', resetForm);
        
        function resetForm() {
            form.reset();
            preview.classList.add('hidden');
            imagePreview.src = '#';
            fileName.textContent = '';
            submitBtn.disabled = true;
            result.classList.add('hidden');
            ktpData.innerHTML = '';
            alertContainer.innerHTML = '';
            extractedData = null;
            
            // Scroll back to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    </script>
</body>
</html>
