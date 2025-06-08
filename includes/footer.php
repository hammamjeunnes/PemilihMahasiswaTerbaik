        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 font-medium mb-2">KELOMPOK 2 - SI 23 R2</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 text-sm text-gray-500">
                    <div>HAMMAM JITAPSARA (2304140050)</div>
                    <div>YUDHISTIRA LUCKY OKTAVIAN (2304140080)</div>
                    <div>ARIF SATRIA TAMA (2304140063)</div>
                    <div>AISYAH WILAVY ZAHRA (2304140068)</div>
                </div>
                <p class="mt-4 text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> Sistem Pendukung Keputusan - Pemilihan Mahasiswa Terbaik
                </p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Hapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Tampilkan notifikasi jika ada parameter di URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                showNotification('Data berhasil disimpan!', 'success');
            }
        });
    </script>
</body>
</html>
