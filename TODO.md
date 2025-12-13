# TODO: Fix Stock Issues in Kasir and Admin Produk

## Issues Identified:
1. **Admin Produk View**: Displays `$p->stock` instead of `$p->stok` (model attribute mismatch).
2. **Admin Tambah Produk**: Stok field not saved in `simpanProduk` method (missing validation and create).
3. **Customer Orders**: Stok not reduced when customers place orders (only reduced in Kasir for cash sales).
4. **Tambah Produk View**: Incorrect default value for stok field.

## Plan:
1. **Fix Admin Produk View**: Change `$p->stock` to `$p->stok` in `resources/views/admin/produk.blade.php`.
2. **Fix AdminController simpanProduk**: Add 'stok' and 'deskripsi' to validation and create in `app/Http/Controllers/AdminController.php`.
3. **Fix Tambah Produk View**: Correct stok field default value in `resources/views/admin/tambah_produk.blade.php`.
4. **Add Stok Reduction in Customer Orders**: Reduce stok when orders are created in `app/Http/Controllers/CustomerController.php` checkout method.

## Followup Steps:
- Test adding product with stok in admin.
- Test customer order and verify stok reduction.
- Test kasir checkout and verify stok update.
- Verify admin produk list shows correct stok.
