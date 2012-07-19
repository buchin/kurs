# Kurs

Sebuah API (Application Programming Interface) untuk mengetahui nilai tukar rupiah terhadap beberapa mata uang asing. 
Untuk saat ini, data diambil dari Bank Central Asia.

Untuk saat ini hasil bisa dilihat di http://kurs.dropsugar.com/rates/bca.json atau http://kurs.dropsugar.com/rates/bca.jsonp?callback=namafungsi

## Alasan membuat API ini

Banyak website yang membutuhkan data nilai tukar rupiah terhadap mata uang asing, terutama USD. Namun, server mereka tidak cukup kuat untuk scraping data secara langsung. Bisa jadi, keterbatasan sumber daya programmer juga mempengaruhi.

Terlebih lagi, bank-bank di indonesia sebagai rujukan tidak menyediakan sarana yang memudahkan developer untuk memakai data mereka. Hal ini cukup disayangkan karena banyaknya permintaan akan data ini.

Berbagai contoh skenario:

* Para web publisher di Indonesia banyak yang memakai BCA sebagai rujukan ketika mau jual beli paypal. Alangkah lebih baik jika di lapak-lapak mereka, terutama forum, ada widget untuk melihat nilai tukar hari ini.
* Banyak toko online yang masih memakai dolar sebagai tolok ukur harga jual, terutama komputer dan asesorisnya. API bisa dipakai sebagai rujukan dan bisa diotomatisasi agar harga bisa dilihat sebagai rupiah.
* Setiap akhir bulan, publisher adsense selalu memeriksa rate Western Union sebelum mencairkan dananya.

## Berbagai keterbatasan

* API ini masih jauh dari sempurna. Karena saat ini hanya menyediakan data dari BCA. Masih diperlukan berbagai sumber lain, terutama Western Union.
* Waktu. Saya bisa mengembangkan API ini di luar jam kerja saya. Berbagai ide muncul di otak, seperti membuat widget untuk WordPress, membuat aplikasi Android / iOS dari API ini. Namun, karena keterbatasan waktu, saya hanya bisa mengupdate ini pelan-pelan. Bagi yang ingin memanfaatkan API ini untuk keperluan itu, saya sangat terbuka dan senang jika ada yang bisa memanfaatkan API ini.
* Biaya. Saat ini server saya tempatkan di Pagodabox yang versi gratis, sekalipun versi gratis, saya bisa menjamin bahwa server ini bisa menampung ribuan - puluhan ribu request sehari karena sistem caching.
* Update satu jam sekali. Untuk mengatasi lonjakan traffic, saya memakai sistem caching dimana data akan disingkronkan dengan sumber data asli ( misal, bca ) selama satu jam sekali. Cache memakai memcache, kode bisa anda lihat di repositori ini.
* Masih perlu beberapa refactor. Saya sadari kode ini masih kurang rapi. Perlu dilakukan refactor untuk memindahkan beberapa kode di dalam folder controller. Namun, saat ini saya merasa hal ini masih belum dibutuhkan. Refactor akan dilakukan ketika sudah semakin kompleks. (Asline sek males bro, LOL).
* Kode masih platform specific ke pagodabox. 

## Contoh pemakaian:
### PHP

````
<?php
$response =  json_decode(file_get_contents('http://kurs.dropsugar.com/rates/bca.json'));
echo "Terakhir diupdate pada: " . date('j-m-Y H:i:s');
foreach ($response->kurs as $currency => $value) {
    echo 'Nilai jual ' . $currency . ' adalah: ' . $value->jual . '<br>';
    echo 'Nilai beli ' . $currency . ' adalah: ' . $value->beli . '<br><br>';
}
````

### Contoh lain PHP, lebih singkat

Contoh ini cocok untuk keperluan kecil

````
<?php
$response =  json_decode(file_get_contents('http://kurs.dropsugar.com/rates/bca.json'));
echo "Kurs jual USD hari ini: " . $response->kurs->USD->jual . '<br>';
echo "Kurs beli USD hari ini: " . $response->kurs->USD->beli . '<br>';
````

### jQuery
Contoh ini blom kutes :D, namun garis besarnya akan seperti ini

````
$(function(){
    $.getJSON('http://kurs.dropsugar.com/rates/bca.jsonp?callback=?', function(data){
        //data sudah didapat, kita update tag div dengan ID usd-jual dan usd-beli
        $('#usd-jual').html(data.kurs.BCA.jual);
        $('#usd-beli').html(data.kurs.BCA.beli);

        // untuk keperluan debug, hasil panggil API bisa dilihat di javascript console
        console.log(data);
    });
});
````

## Penutup
Jika anda ingin kontribusi ke kode ini, silakan fork dan kirim pull request. Semoga Allah membalas siapapun yang mau kontribusi ke kode ini agar makin berguna bagi masyarakat.

Saya sadar aplikasi ini masih belum sempurna, secara pribadi, saya ingin memasukkan berbagai sumber, seperti Bank Syariah. Namun, saya haru memulai dari instansi yang umum dipakai.

## Ucapan terimasih & tautan

Orang tuaku, istriku Lara Asih Mulya, asistenku Moeghan Diantok beserta Rinah, Rizky Ramadhan beserta Maylatun Sari, Ari Sandi Robert, Adrian Sandi, Momod Ari, Leonardo Rony S, Aa Gym, Yusuf Mansyur, Guru-guru saya, kakek buyut, anak cucu, dan semua teman TC 05, rekan-rekan iMers dan IMMatic, saudara-saudara serta semua pihak yang belum saya sebut di sini.

* http://dropsugar.com
* http://amplop.in
* http://troli.in

## Donasi

Anda merasa aplikasi ini berguna? Saya menerima donasi via Bank, PayPal dan Gittip, sila hubungi buchin@dropsugar.com.
Donasi anda akan saya buat untuk kelangsungan project ini, seperti biaya server dan lain-lain.

## Lisensi
Free software, sila gunakan untuk keperluan yang halal :p