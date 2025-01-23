import hashlib

def md5_decode(md5_hash, wordlist_file):
    """
    Fungsi untuk mendekode hash MD5 menggunakan wordlist.
    
    Args:
    - md5_hash (str): Hash MD5 yang akan dicari.
    - wordlist_file (str): Path ke file wordlist.
    
    Returns:
    - str: Kata yang cocok dengan hash MD5.
    """
    try:
        # Membuka file wordlist
        with open(wordlist_file, 'r') as file:
            for word in file:
                word = word.strip()  # Menghapus karakter newline atau spasi
                # Hash kata dan cocokkan dengan MD5
                if hashlib.md5(word.encode()).hexdigest() == md5_hash:
                    return f"Kata yang cocok: {word}"
        return "Hash MD5 tidak ditemukan di dalam wordlist."
    except FileNotFoundError:
        return "File wordlist tidak ditemukan. Periksa path file."

# Hash MD5 yang ingin didekodekan
md5_hash_to_decode = "lnaPGS{pbzry_synt_PFF}"  # Contoh hash untuk kata 'hello'

# Path ke file wordlist Anda
wordlist_file_path = "wordlist.txt"  # Ganti dengan path ke file wordlist Anda

# Menjalankan fungsi decode
result = md5_decode(md5_hash_to_decode, wordlist_file_path)
print(result)

