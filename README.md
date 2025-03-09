<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## ===== Cara setup dan menjalankan aplikasi =====

## Login Admin
1.	Ketika menjalankan aplikasi maka akan diarahkan atau masuk halaman login, pada aplikasi ini sudah saya buatkan seeder untuk admin. Jadi admin tinggal input email dan password pada halaman login, maka admin bisa masuk ke dashboard admin. Namun jika salah input email dan password maka akan menampilkan pesan “Email atau password salah”.
2.	Setelah menginputkan email dan password yang benar maka akan masuk ke halaman dashboard admin. Ketika berhasil masuk akan menampilkan notif “Login success sebagai admin”. Di halaman ini menampilkan 4 sidebar yaitu Dashboard, Jadwal Travel, Laporan, dan Logout. Pada halaman dashboard, menampilkan data seperti: Jadwal Aktif, Total Penumpang, Tiket Terjual, Pendapatan. Lalu ada table Jadwal Travel Terbaru yang menampilkan data travel seperti tujuan, tanggal, waktu, kuota, terisi, harga, status (penuh dan aktif), dan aksi. Lalu ada chart Laporan Penumpang dan Penumpang Terbaru.
3.	Admin dapat melakukan CRUD jadwal travel dengan masuk ke halaman jadwal travel dengan menekan sidebar Jadwal Travel. Di halaman Jadwal Travel si admin bisa membuat jadwal travel dengan menekan button tambah jadwal yang mana nanti akan masuk ke halaman tambah jadwal travel. Kemudian admin bisa mengisi data pada halaman ini. Setelah di isi dan di simpan maka akan Kembali ke halaman jadwal travel. Dan admin dapat melihat data yang telah berhasil dibuat. Lalu jika data salah maka admin bisa mengedit data tersebut dengan menekan button edit dan akan masuk ke halaman Edit Jadwal Travel. Disini admin dapat mengedit jadwal, lalu setelah selesai di edit maka klik button perbarui jadwal sehingga data jadwal tersebut berhasil di update dan akan Kembali ke halaman Jadwal Travel dengan menampilkan data yang telah di perbarui. Lalu jika admin ingin menghapus maka tinggal klik button hapus maka akan menampilkan alert konfirmasi hapus. Jika ingin menghapus maka klik Ya, hapus pada alert konfirmasi hapus tersebut. Di halaman jadwal travel, admin juga dapat melakukan search atau filtering untuk mencari jadwal travel sehingga memudahkan admin.
4.	Lalu di sidebar Laporan, admin dapat melihat Laporan Jumlah Penumpang Per Travel. Di halaman ini, admin akan melihat informasi seperti: nama travel, tanggal keberangkatan, jumlah penumpang dan button detail untuk masuk ke halaman detail laporan.
5.	Lalu di halaman detail laporan, admin dapat melihat data detail laporan seperti: nama penumpang, email, no telp, kursi yang dipesan, status pembayaran, nama travel, tanggal keberangkatan, jumlah penumpang.
6.	Lalu jika admin ingin logout maka tinggal klik sidebar logout atau profile maka akan muncul dropdown yang ada logoutnya.

## Login Penumpang
1.	Ketika masuk aplikasi ini maka si penumpang akan masuk ke halaman login. Berhubung belum membuat akun maka bisa klik Daftar di sini pada halaman login. Maka akan masuk ke halaman register setelah menekan button tersebut. Di halaman register, si penumpang dapat mengisi nama lengkap, email, no telp, alamat, password lalu klik Daftar sekarang. Jika sudah maka akan masuk ke halaman login dengan menampilkan pesan register success. Kemudian di halaman login, si penumpang tinggal masukin data yang telah dibuat tadi yaitu email dan password. Setelah mengisi data dengan benar dan klik login maka akan masuk ke halaman dashboard penumpang dan akan menampilkan pesan “Login success sebagai customer” lalu menampilkan nama penumpang yang sedang login di navbar.
2.	Di halaman dashboard ini ada 3 sidebar yaitu Dashboard History, dan Logout. Di halaman dashboard ini akan menampilkan semua jadwal travel yang telah dibuat oleh admin. Lalu penumpang dapat melakukan search dan filter untuk membantu mencari jadwal travel dengan mudah. Lalu juga menampilkan data jadwal yang tersedia dengan menekan nav tersedia. Di dashboard ini jadwal travel muncul secara lengkap yaitu menampilkan tujuan lokasi, tanggal keberangkatan, jumlah kuota, harga. Lalu penumpang dapat melakukan booking di sini dengan mengisi jumlah kursi yang ingin dipesan dan juga ada max nya sesuai jumlah kuota yang tersedia. Setelah mengisi jumlah kursi yang akan dipesan, kemudian klik button pesan tiket maka akan masuk ke halaman booking. Jika jadwal travel kuota masih banyak maka status tersedia, jika hampir habis maka status hampir habis dan jika kuota habis maka jadwal tersebut akan habis / sold dan penumpang tidak bisa melakukan booking pada jadwal yang telah habis.
3.	Setelah menekan button pesan tiket akan masuk ke halaman booking. Disini akan muncul data jadwal travel yang telah dipesan dan menampilkan informasi seperti: tujuan, waktu keberangkatan, jumlah kursi, status pembayaran, metode pembayaran. Lalu jika penumpang ingin membayar tinggal memilih metode pembayaran dan menekan button konfirmasi pembayaran. Setelah itu maka akan muncul pesan “Pembayaran berhasil dikonfirmasi” dan status pembayaran berubah dari menunggu pembayaran menjadi tiket terbayar. Lalu buttonnya juga berubah menjadi Lihat tiket jika sudah membayar. 
4.	Kemudian penumpang dapat menekan button Lihat Tiket untuk masuk ke halaman History atau bisa juga masuk lewat sidebar History. Di halaman history ini menampilkan informasi semua transaksi yang dilakukan penumpang seperti: tujuan, tanggal keberangkatan, jumlah kursi, total harga, status pembayaran, dan aksi. Untuk aksi ada 2 yaitu Invoice jika status pembayaran sudah dibayar dan bayar jika status pembayaran masih menunggu pembayaran. Jika invoice maka akan masuk ke halaman invoice jika button tersebut di klik. Dan ketika button bayar di klik akan masuk ke halaman booking. Di halaman history juga ada fitur search untuk membantu memudahkan penumpang mencari data riwayat transaksinya. 
5.	Button invoice akan mengarahkan penumpang masuk ke halaman invoice, di halaman invoice ini menampilkan informasi tanggal booking, id pembayaran, detail pemesanan yaitu nama penumpang dan email, detail perjalanan yaitu tujuan dan tanggal keberangkatan. Lalu ada detail booking yang menampilkan deskripsi / tujuan, jumlah kursi , harga/kursi, dan total harga. Ada juga metode pembayaran yang digunakan, status pembayaran dan subtotal. Lalu penumpang dapat mendownload invoice dengan menekan button download invoice.
6.	Lalu jika penumpang ingin logout maka tinggal klik sidebar logout atau profile maka akan muncul dropdown yang ada logoutnya.

