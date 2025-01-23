import itertools
import string
import time

# Mulai menghitung waktu pembuatan
start_time = time.time(

# Semua huruf yang akan digunakan (lowercase atau uppercase)
letters = string.ascii_lowercase

# Buat kombinasi huruf 3
wordlist = itertools.product(letters, repeat=3)

# Simpan hasilnya ke dalam file
with open('wordlist.txt', 'w') as f:
    for word in wordlist:
        f.write(''.join(word) + '\n')

# Hitung waktu yang dibutuhkan
end_time = time.time()
print(f"Wordlist dengan 3 huruf telah dibuat dalam {end_time - start_time:.2f} detik.")
