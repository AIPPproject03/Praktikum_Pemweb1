// 1. Buatlah sebuah variabel list di dalam sebuah map dan tampilkan hasilnya.

void soal1() {
  // Jawaban
  Map<String, List<int>> list = {
    'angka': [1, 2, 3, 4, 5],
  };

  print(list);
}

// 2. Buatlah sebuah variabel list yang berisikan map dan tampilkan hasilnya.
void soal2() {
  // Jawaban
  List<Map<String, dynamic>> list = [
    {'nama': 'Andi', 'umur': 21},
    {'nama': 'Budi', 'umur': 22},
    {'nama': 'Cisca', 'umur': 23},
  ];

  print(list);
}
// 3. Tambah, edit dan hapus pada variable list berikut:
// void main() {
//  List<int> angka = [1, 2, 3, 4, 5];
// }

void soal3() {
  // Jawaban
  List<int> angka = [1, 2, 3, 4, 5];

  // Tambah
  angka.add(6);

  // Edit
  angka[0] = 10;

  // Hapus
  angka.remove(2);

  print(angka);
}

// Buatlah menu pilihan untuk mengeksekusi soal-soal di atas.
void main() {
  // soal1();
  // soal2();
  soal3();
}
