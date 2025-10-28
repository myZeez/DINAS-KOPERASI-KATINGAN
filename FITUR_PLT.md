# 📋 Fitur PLT (Pelaksana Tugas) - Struktur Organisasi

## ✅ Fitur yang Ditambahkan

### 1. **Database**
File Migration: `2025_10_28_093517_add_plt_fields_to_structures_table.php`

**Field baru di tabel `structures`:**
- `is_plt` (boolean) - Status apakah jabatan sedang dijabat PLT
- `plt_from_structure_id` (bigint, nullable) - ID struktur yang menjabat PLT
- `plt_name` (varchar, nullable) - Nama PLT (jika manual/dari luar)
- `plt_nip` (varchar, nullable) - NIP PLT (jika manual)
- `plt_rank` (varchar, nullable) - Pangkat PLT (jika manual)
- `plt_start_date` (date, nullable) - Tanggal mulai PLT
- `plt_end_date` (date, nullable) - Tanggal selesai PLT (estimasi)
- `plt_notes` (text, nullable) - Keterangan PLT

### 2. **Model**
File: `app/Models/Structure.php`

**Relasi baru:**
- `pltFromStructure()` - Relasi ke struktur yang menjabat PLT
- `structuresAsPlt()` - Relasi ke jabatan yang dijabat sebagai PLT

### 3. **Controller**
File: `app/Http/Controllers/Admin/StructureController.php`

**Update method:**
- `store()` - Validasi dan simpan data PLT
- `update()` - Validasi dan update data PLT
- `show()` - Load relasi PLT saat view detail

### 4. **View**
File: `resources/views/admin/structure/index.blade.php`

**Form PLT Features:**

#### a. **Checkbox Toggle PLT**
- Checkbox untuk mengaktifkan status PLT
- Saat diaktifkan, muncul form tambahan PLT

#### b. **2 Opsi Input PLT:**

**Opsi 1: Ambil dari Jabatan yang Ada**
- Dropdown list jabatan aktif yang ada
- Otomatis ambil data dari struktur yang dipilih

**Opsi 2: Input Manual**
- Untuk PLT dari luar dinas atau jabatan lain
- Input: Nama, NIP, Pangkat/Golongan

#### c. **Form Tambahan PLT:**
- Tanggal Mulai PLT
- Tanggal Selesai PLT (estimasi)
- Keterangan PLT (SK, alasan, dll)

## 🎯 Cara Penggunaan

### **Menambah/Edit Struktur dengan PLT:**

1. **Buka form tambah/edit struktur**
2. **Aktifkan checkbox "PLT (Pelaksana Tugas)"**
3. **Pilih sumber PLT:**
   - **Dari Jabatan yang Ada**: Pilih pejabat dari dropdown
   - **Input Manual**: Isi nama, NIP, pangkat secara manual
4. **Isi tanggal dan keterangan PLT**
5. **Simpan**

## 📊 Validasi

- `plt_end_date` harus setelah atau sama dengan `plt_start_date`
- Jika PLT aktif dengan opsi "existing", `plt_from_structure_id` harus valid
- Jika PLT aktif dengan opsi "manual", `plt_name` wajib diisi

## 🔍 Contoh Penggunaan

### **Scenario 1: Kepala Dinas Sedang Cuti**
```
Jabatan: Kepala Dinas Koperinsi...
Status PLT: ✅ Aktif
PLT dari: Sekretaris Dinas (pilih dari dropdown)
Tanggal Mulai: 2025-01-01
Tanggal Selesai: 2025-01-31
Keterangan: Kepala Dinas sedang cuti, SK PLT No. 123/2025
```

### **Scenario 2: PLT dari Luar Dinas**
```
Jabatan: Kepala Bidang Koperasi dan UMKM
Status PLT: ✅ Aktif
PLT Manual: 
  - Nama: Dr. Ahmad Hidayat, S.E., M.M.
  - NIP: 197801011998031001
  - Pangkat: Pembina Tingkat I - IV/b
Tanggal Mulai: 2025-02-01
Keterangan: Pejabat dari Dinas Perindustrian, SK Bupati No. 456/2025
```

## 🎨 UI Features

- ✅ Toggle smooth dengan animasi slide
- ✅ Radio button untuk switch antara existing/manual
- ✅ Form validation otomatis
- ✅ Reset field saat checkbox PLT dinonaktifkan
- ✅ Auto-populate data saat edit

## ⚡ JavaScript Events

- `#is_plt change` - Toggle tampilan form PLT
- `input[name="plt_source"] change` - Switch antara existing/manual
- Auto-reset PLT fields saat checkbox dinonaktifkan

## 🔐 Security

- Foreign key constraint untuk `plt_from_structure_id`
- Validasi tanggal di backend
- Sanitasi input untuk mencegah XSS

---

**Status:** ✅ Fitur sudah selesai dan siap digunakan
**Tested:** ⏳ Belum di-test (perlu test manual)
