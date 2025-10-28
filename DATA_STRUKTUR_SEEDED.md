# ✅ DATA STRUKTUR ORGANISASI BERHASIL DIMASUKKAN KE DATABASE

**Tanggal:** 28 Oktober 2025
**Total Data:** 17 Pegawai

---

## 📊 STRUKTUR ORGANISASI DINAS KOPERINSI, USAHA KECIL MENENGAH DAN PERDAGANGAN KABUPATEN KATINGAN

### **LEVEL 1 - KEPALA DINAS (1 orang)**

1. **YODIHEL, S.E., M.Si**
   - Jabatan: Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan
   - Level: 1
   - Status: Aktif

---

### **LEVEL 2 - ESELON II (5 orang)**

2. **Drs. SETIADY YUYU** 🕐 **[PLT]**
   - Jabatan: Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten
   - Level: 2
   - Status: PLT (Pelaksana Tugas)
   - Catatan: PLT dari Kepala Bidang Pengembangan Perdagangan

3. **BENYAMIN FRANKLIN JAKOB, S.E**
   - Jabatan: Kepala Bidang Koperasi dan UMKM
   - Level: 2
   - Status: Aktif

4. **Drs. SETIADY YUYU**
   - Jabatan: Kepala Bidang Pengembangan Perdagangan
   - Level: 2
   - Status: Aktif
   - Catatan: Merangkap sebagai PLT Sekretaris

5. **ALEXANDRA SUHARYONO MIKA LAMBANG, S.Si.Pi**
   - Jabatan: Kepala Bidang Kemetrologian
   - Level: 2
   - Status: Aktif

6. **SIRMANIK STEPHAN, A.Md**
   - Jabatan: Fungsional Perencana Ahli Muda (JPT Perencana)
   - Level: 2
   - Status: Aktif

---

### **LEVEL 3 - ESELON III & STAFF (11 orang)**

#### **A. Sub Bagian Umum dan Kepegawaian**
7. **RITHA IRIANI, S.H**
   - Jabatan: Kepala Sub Bagian Umum dan Kepegawaian
   - Parent: Sekretaris
   - Status: Aktif

#### **B. Sub Bagian Keuangan dan Pelaporan**
8. **ESRA, S.Sos**
   - Jabatan: Kepala Sub Bagian Keuangan dan Pelaporan
   - Parent: Sekretaris
   - Status: Aktif

#### **C. Bidang Koperasi dan UMKM (3 JFT)**
9. **AGUS MAULUDIN, S.P., M.P**
   - Jabatan: JFT Pengawas Koperasi Ahli Muda
   - Parent: Kepala Bidang Koperasi dan UMKM
   - Status: Aktif

10. **EKO ADHI NUGROHO, S.E**
    - Jabatan: JFT Pengawas Koperasi Ahli Muda
    - Parent: Kepala Bidang Koperasi dan UMKM
    - Status: Aktif

11. **RAHMAD J. RAHMALI, S.E**
    - Jabatan: JFT Pengawas Koperasi Ahli Muda
    - Parent: Kepala Bidang Koperasi dan UMKM
    - Status: Aktif

#### **D. Bidang Pengembangan Perdagangan (3 JFT)**
12. **KOSONG** ⚠️
    - Jabatan: JFT Pengawas Perdagangan Ahli Muda
    - Parent: Kepala Bidang Pengembangan Perdagangan
    - Status: **TIDAK AKTIF** (Posisi Kosong)

13. **YONI MARIANAE, S.Pi**
    - Jabatan: JFT Pengawas Perdagangan Ahli Muda
    - Parent: Kepala Bidang Pengembangan Perdagangan
    - Status: Aktif

14. **SODERIYANTO, S.H Adv**
    - Jabatan: JFT Pengawas Perdagangan Ahli Muda
    - Parent: Kepala Bidang Pengembangan Perdagangan
    - Status: Aktif

#### **E. Bidang Kemetrologian (3 JFT)**
15. **MANOGAR SIMANULLANG, S.T**
    - Jabatan: JFT Penera Ahli Muda
    - Parent: Kepala Bidang Kemetrologian
    - Status: Aktif

16. **YUBILIANTO, S.T**
    - Jabatan: JFT Penera Ahli Muda
    - Parent: Kepala Bidang Kemetrologian
    - Status: Aktif

17. **SUPRIATNA, SKM**
    - Jabatan: JFT Pengawas Kemetrologian Ahli Muda
    - Parent: Kepala Bidang Kemetrologian
    - Status: Aktif

---

## 📈 STATISTIK

| Kategori | Jumlah |
|----------|--------|
| **Total Pegawai** | 17 orang |
| **Level 1 (Kepala Dinas)** | 1 orang |
| **Level 2 (Eselon II)** | 5 orang |
| **Level 3 (Eselon III & Staff)** | 11 orang |
| **PLT (Pelaksana Tugas)** | 1 orang |
| **Posisi Kosong** | 1 posisi |
| **Jabatan Fungsional Tertentu (JFT)** | 9 orang |

---

## 🎯 CATATAN KHUSUS

1. **PLT Sekretaris**: Drs. SETIADY YUYU menjabat sebagai PLT Sekretaris sambil merangkap sebagai Kepala Bidang Pengembangan Perdagangan.

2. **Posisi Kosong**: JFT Pengawas Perdagangan Ahli Muda (1 posisi) masih kosong dan ditandai dengan status `is_active = false`.

3. **Struktur Hierarki**:
   - Kepala Dinas → Sekretaris → Sub Bagian (2)
   - Kepala Dinas → Kepala Bidang (3) → JFT/Staff (9)
   - Kepala Dinas → JPT Perencana (1)

---

## 🔄 CARA MENGGUNAKAN

### **Melihat Data di Admin:**
1. Login ke admin panel
2. Buka menu "Struktur Organisasi"
3. Lihat bagan organisasi lengkap dengan badge PLT

### **Melihat di Halaman Publik:**
1. Buka website
2. Klik menu "Struktur"
3. Badge PLT akan terlihat pada Sekretaris

### **Re-seed Database (Jika Perlu):**
```bash
php artisan db:seed --class=StructureSeeder
```

---

**File Seeder:** `database/seeders/StructureSeeder.php`
**Status:** ✅ Data berhasil dimasukkan ke database
