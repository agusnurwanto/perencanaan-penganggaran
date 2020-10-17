##tabel ta__belanja

SELECT
	ta__belanja.id,
	ta__belanja.id_keg,
	ta__belanja.id_rek_5,
	ta__belanja.id_sumber_dana
FROM
	ta__belanja
WHERE
	ta__belanja.id_keg = 1 // ambil dari source
	
	
	
##tabel ta__belanja_sub
SELECT
	ta__belanja_sub.id,
	belanja.id_belanja,
	ta__belanja_sub.kd_belanja_sub,
	ta__belanja_sub.uraian
FROM
	ta__belanja_sub
INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
LEFT JOIN (
	SELECT
		ta__belanja.id AS id_belanja,
		ta__belanja.id_rek_5
	FROM
		ta__belanja
	WHERE
		ta__belanja.id_keg = 2 // ambil dari destination
) AS belanja ON belanja.id_rek_5 = ta__belanja.id_rek_5
WHERE
	ta__belanja.id_keg = 1 // ambil dari source
	

	

##tabel ta__belanja_rinc
SELECT
	ta__belanja_rinc.id,
	belanja_sub.id_belanja_sub,
	ta__belanja_rinc.id_standar_harga,
	ta__belanja_rinc.kd_belanja_rinc,
	ta__belanja_rinc.uraian,
	ta__belanja_rinc.vol_1,
	ta__belanja_rinc.vol_2,
	ta__belanja_rinc.vol_3,
	ta__belanja_rinc.satuan_1,
	ta__belanja_rinc.satuan_2,
	ta__belanja_rinc.satuan_3,
	ta__belanja_rinc.nilai,
	ta__belanja_rinc.vol_123,
	ta__belanja_rinc.satuan_123,
	ta__belanja_rinc.total
FROM
	ta__belanja_rinc
INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
LEFT JOIN (
	SELECT
		ta__belanja_sub.id AS id_belanja_sub
		ta__belanja_sub.kd_belanja_sub
	FROM
		ta__belanja_sub
	INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
	WHERE
		ta__belanja.id_keg = 2 // ambil dari destination
) AS belanja_sub ON belanja_sub.kd_belanja_sub = ta__belanja_sub.kd_belanja_sub
WHERE
	ta__belanja.id_keg = 1 // ambil dari source