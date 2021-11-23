<!-- menghitung jumlah karyawan verifikator pada table penempatan -->
SELECT k.nama_lengkap, "count"(p.id_penempatan) as total
FROM karyawan as k
LEFT JOIN penempatan as p ON p.id_karyawan = k.id_karyawan
GROUP BY k.id_karyawan
ORDER BY total DESC

<!-- Peringkat Verifkator == Jumlah recoveries terbanyak -->
SELECT k.nama_lengkap, "sum"(r.nominal) as total_recoveries
FROM karyawan as k
LEFT JOIN penempatan as p ON p.id_karyawan = k.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d ON d.id_capem_bank = p.id_capem_bank 
LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur
GROUP BY k.id_karyawan
ORDER BY total_recoveries DESC
LIMIT 10

<!-- halaman NOA -->
SELECT d.nama_debitur, cab.cabang_bank, d.subrogasi as subro, "sum"(r.nominal) as total_nominal_recoveries, ms.status_deb
FROM debitur as d
JOIN recoveries as r ON r.id_debitur = d.id_debitur
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
LEFT JOIN ots_status_debitur as sd ON sd.id_ots = o.id_ots
LEFT JOIN m_status_debitur as ms ON ms.id_status_deb = sd.id_status_deb
GROUP BY d.id_debitur, cab.id_cabang_bank, ms.id_status_deb
ORDER BY nama_debitur ASC

==== Mencari tindakan hukum

SELECT "count"(mh.tindakan_hukum ), mh.tindakan_hukum ,"sum"(r.nominal) as total_nominal_recoveries
FROM debitur as d
LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
LEFT JOIN ots_status_debitur as sd ON sd.id_ots = o.id_ots
LEFT JOIN m_status_debitur as ms ON ms.id_status_deb = sd.id_status_deb
LEFT JOIN tr_ots_p as op ON op.id_ots = o.id_ots
LEFT JOIN tr_tindakan_hukum as th ON th.id_tr_ots_p = op.id_tr_ots_p
LEFT JOIN m_tindakan_hukum as mh ON mh.id_tindakan_hukum = th.id_tindakan_hukum

GROUP BY mh.id_tindakan_hukum

<!-- halaman r proses -->
SELECT d.nama_debitur, cab.cabang_bank, fu.tgl_fu, mth.tindakan_hukum, sa.status_asset
FROM tr_tindakan_hukum as tth
LEFT JOIN m_tindakan_hukum as mth ON mth.id_tindakan_hukum = tth.id_tindakan_hukum
LEFT JOIN tr_ots_p as op ON op.id_tr_ots_p = tth.id_tr_ots_p
LEFT JOIN tr_ots_fu as fu ON fu.id_tr_ots_p = tth.id_tr_ots_p 
LEFT JOIN ots as o ON o.id_ots = op.id_ots
LEFT JOIN debitur as d ON d.id_debitur = o.id_debitur
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN tr_asset as ta ON ta.id_debitur = d.id_debitur
LEFT JOIN status_asset as sa ON sa.id_status_asset = ta.status

<!-- count jumlah tindakan hukum -->

SELECT mth.tindakan_hukum, "count"(mth.tindakan_hukum) as jumlah_tindakan
FROM tr_tindakan_hukum as tth
LEFT JOIN m_tindakan_hukum as mth ON mth.id_tindakan_hukum = tth.id_tindakan_hukum
LEFT JOIN tr_ots_p as op ON op.id_tr_ots_p = tth.id_tr_ots_p
LEFT JOIN tr_ots_fu as fu ON fu.id_tr_ots_p = tth.id_tr_ots_p 
LEFT JOIN ots as o ON o.id_ots = op.id_ots
LEFT JOIN debitur as d ON d.id_debitur = o.id_debitur
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN tr_asset as ta ON ta.id_debitur = d.id_debitur
LEFT JOIN status_asset as sa ON sa.id_status_asset = ta.status
GROUP BY mth.id_tindakan_hukum


===================

SELECT k.nama_lengkap, cap.capem_bank,d.nama_debitur, "sum"(r.nominal)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur

GROUP BY k.nama_lengkap, cap.capem_bank, d.nama_debitur

-- untuk total recoveries 
SELECT k.nama_lengkap, cap.capem_bank, "sum"(r.nominal)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'

GROUP BY k.nama_lengkap, cap.capem_bank

-- untuk sum subrogasi
SELECT k.nama_lengkap, cap.capem_bank, "sum"(d.subrogasi)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'

GROUP BY k.nama_lengkap, cap.capem_bank

-- untuk total harga tr asset
SELECT k.nama_lengkap, cap.capem_bank, "sum"(ta.total_harga)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN tr_asset as ta ON ta.id_debitur = d.id_debitur

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'

GROUP BY k.nama_lengkap, cap.capem_bank

-- Mencari berapa yang masuk tr_tindakan_hukum
SELECT k.nama_lengkap, cap.capem_bank, "count"(mh.tindakan_hukum)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN tr_asset as a ON a.id_debitur = d.id_debitur
LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
left join tr_ots_p as op ON op.id_ots = o.id_ots
left join tr_tindakan_hukum as th ON th.id_tr_ots_p = op.id_tr_ots_p
left JOIN m_tindakan_hukum as mh ON mh.id_tindakan_hukum = th.id_tindakan_hukum

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'

GROUP BY k.nama_lengkap, cap.capem_bank

-- total somasi
SELECT k.nama_lengkap, cap.capem_bank, "count"(mh.tindakan_hukum)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN tr_asset as a ON a.id_debitur = d.id_debitur
LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
left join tr_ots_p as op ON op.id_ots = o.id_ots
left join tr_tindakan_hukum as th ON th.id_tr_ots_p = op.id_tr_ots_p
left JOIN m_tindakan_hukum as mh ON mh.id_tindakan_hukum = th.id_tindakan_hukum

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'
AND mh.tindakan_hukum like '%Somasi%'

GROUP BY k.nama_lengkap, cap.capem_bank

==== jumlah asset menurut verfikator
SELECT k.nama_lengkap, cap.capem_bank, "count"(ta.id)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN tr_asset as ta ON ta.id_debitur = d.id_debitur
LEFT JOIN status_asset as sa ON sa.id_status_asset = ta.status

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'

GROUP BY k.nama_lengkap, cap.capem_bank

==== sum total harga status terjual
SELECT k.nama_lengkap, cap.capem_bank, "count"(ta.id), "sum"(ta.total_harga)
FROM penempatan as p
LEFT JOIN karyawan as k ON k.id_karyawan = p.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN debitur as d  ON d.id_capem_bank = cap.id_capem_bank
LEFT JOIN tr_asset as ta ON ta.id_debitur = d.id_debitur
LEFT JOIN status_asset as sa ON sa.id_status_asset = ta.status

WHERE k.nama_lengkap = 'Joni' AND cap.capem_bank = 'KCU Bandung'
AND sa.status_asset = 'Sudah Terjual'

GROUP BY k.nama_lengkap, cap.capem_bank

-- by cabang
SELECT k.nama_lengkap,cab.cabang_bank,  "sum"(r.nominal) as total_recoveries
FROM karyawan as k
LEFT JOIN penempatan as p ON p.id_karyawan = k.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN debitur as d ON d.id_capem_bank = p.id_capem_bank 
INNER JOIN recoveries as r ON r.id_debitur = d.id_debitur
-- WHERE total_recoveries = '1000000'

GROUP BY k.id_karyawan, cab.id_cabang_bank
ORDER BY total_recoveries DESC
LIMIT 10

-- by capem
SELECT k.nama_lengkap,cap.capem_bank, "sum"(r.nominal) as total_recoveries
FROM karyawan as k
LEFT JOIN penempatan as p ON p.id_karyawan = k.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN debitur as d ON d.id_capem_bank = p.id_capem_bank 
INNER JOIN recoveries as r ON r.id_debitur = d.id_debitur
-- WHERE total_recoveries = '1000000'

GROUP BY k.id_karyawan, cap.id_capem_bank
ORDER BY total_recoveries DESC
LIMIT 10

-- cabang capem
SELECT k.nama_lengkap,cap.capem_bank, cab.cabang_bank, "sum"(r.nominal) as total_recoveries
FROM karyawan as k
LEFT JOIN penempatan as p ON p.id_karyawan = k.id_karyawan
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = p.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN debitur as d ON d.id_capem_bank = p.id_capem_bank 
INNER JOIN recoveries as r ON r.id_debitur = d.id_debitur
-- WHERE total_recoveries = '1000000'

GROUP BY k.id_karyawan, cap.id_capem_bank, cab.id_cabang_bank
ORDER BY total_recoveries DESC
LIMIT 10

<!-- halaman noa -->
SELECT d.nama_debitur, cab.cabang_bank, d.subrogasi as subro, de.nominal_denda, d.bunga, "sum"(r.nominal) as total_nominal_recoveries, ms.status_deb
FROM debitur as d
LEFT JOIN recoveries as r ON r.id_debitur = d.id_debitur
LEFT JOIN m_capem_bank as cap ON cap.id_capem_bank = d.id_capem_bank
LEFT JOIN m_cabang_bank as cab ON cab.id_cabang_bank = cap.id_cabang_bank
LEFT JOIN ots as o ON o.id_debitur = d.id_debitur
LEFT JOIN ots_status_debitur as sd ON sd.id_ots = o.id_ots
LEFT JOIN m_status_debitur as ms ON ms.id_status_deb = sd.id_status_deb
LEFT JOIN tr_ots_p as op ON op.id_ots = o.id_ots
LEFT JOIN tr_tindakan_hukum as th ON th.id_tr_ots_p = op.id_tr_ots_p
LEFT JOIN m_tindakan_hukum as mh ON mh.id_tindakan_hukum = th.id_tindakan_hukum
LEFT JOIN denda as de ON de.id_debitur = d.id_debitur

WHERE ms.status_deb = 'Tidak Potensial'

GROUP BY d.id_debitur, cab.id_cabang_bank, ms.id_status_deb, de.id_denda
ORDER BY nama_debitur ASC

