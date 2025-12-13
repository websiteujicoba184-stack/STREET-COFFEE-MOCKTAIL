# TODO: Hapus Fitur Dosen dari Aplikasi

## Step 1: Rollback Migration Dosen
- Jalankan `php artisan migrate:rollback --step=1` untuk rollback migration create_dosen_table.
- Verifikasi tabel dosen dihapus dari database.

## Step 2: Hapus File Migration
- Hapus file `database/migrations/2025_11_03_103650_create_dosen_table.php`.

## Step 3: Hapus Model Dosen
- Hapus file `app/Models/Dosen.php`.

## Step 4: Edit AdminController
- Hapus import `use App\Models\Dosen;` di AdminController.php.
- Hapus methods: data_dosen, simpanDosen, editDosen, updateDosen, hapusDosen.

## Step 5: Hapus Views Dosen
- Hapus file `resources/views/admin/data_dosen.blade.php`.
- Hapus file `resources/views/admin/edit_dosen.blade.php`.

## Step 6: Edit Routes
- Buka `routes/web.php` dan hapus routes terkait dosen (misalnya Route::get('/admin/data_dosen', ...), dll.).

## Step 7: Edit Layout Admin
- Buka `resources/views/admin/layout.blade.php` dan hapus link atau menu ke data dosen jika ada.

## Step 8: Test Aplikasi
- Jalankan aplikasi dan pastikan tidak ada error.
- Akses admin dashboard dan pastikan menu dosen hilang.
