# SVM Opinion Retrieval System
Project ini dibuat pada tahun 2012 menggunakan bahasa pemrograman PHP. Project ini ini bertujuan untuk menemukan opini yang relevan sesuai inputan user atau sering disebut Opinion Retrieval dengan menggunakan Support Vector Machine (SVM) dengan Gaussian Kernel.

Dalam project ini dataset yang digunakan dokumen yang berasal dari www.tabloidpulsa.co.id. Halaman situs tersebut memuat banyak artikel dan review yang berisi fakta maupun opini mengenai sebuah gadget yang menjadi target sistem yang dibuat pada project ini. Untuk menentukan kelas dari setiap dokumen yang diperoleh, keseluruhan proses dilakukan secara offline dengan meminta bantuan seorang ahli bahasa.
Tiap dokumen merepresentasikan satu kalimat yang akan diklasifikasikan menjadi fakta ataupun opini. Tahap selanjutnya yaitu pendefinisian stopword dalam database, sehingga atribut yang dihasilkan terbebas dari kata yang sering muncul dan tidak representatif serta tidak memberikan arti penting terhadap isi dokumen.  
![img1](https://user-images.githubusercontent.com/127338607/223895005-0f863e88-17e6-401c-b3bb-a7f660339e00.jpg)

1. **Preprocessing**. Pada tahap ini dilakukan pemfilteran dokumen yaitu dimulai dengan memilih dataset dengan karakteristik yang baik, mengubah format menjadi bentuk plain text, kemudian dilakukan tokenisasi dan penyeragaman besar kecilnya huruf (case folding) untuk kemudian dilakukan penghilangan stopword dan stemming.  

2. **Pembobotan TF-IDF**. Hasil keluaran dari stemming berupa token akan dijadikan atribut acuan dalam penghitungan bobot masing-masing dokumen. Perhitungan menggunakan TF yaitu mengukur jumlah sebuah term dalam sebuah dokumen serta IDF untuk mengukur keunikan sebuah term sebuah dokumen dibandingkan dengan dokumen yang lainnya. Hasil keluarn dari tahap ini berupa data training dan data testing yang menjadi inputan pada tahap berikutnya.

3. **Klasifikasi**. Pada tahap ini data hasil pembobotan di tahap sebelumnya dipcah menjadi data training dan data testing secara acak, kemudia dilakukan proses learning menggunakan metode SVM untuk data training untuk menghasilkan model yang akan digunakan dalam testing nantinya.

4. **Retrieve Dokumen**. Tahap selanjutnya yaitu meretrieve dokumen sesuai query inputan user. Hasil klasifikasi data testing berupa dokumen opini akan dihitung nilai cosine similaritynya terhadap query inputan kemudian dikeluarkan setelah dilakukan perangkingan.

5. **Evaluasi**. Tahap terakhir yaitu evaluasi dari hasil retrieve dokumen oleh sistem, dilihat dari nilai precision dan recall serta akurasinya
