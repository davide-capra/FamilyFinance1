from sympy.ntheory.modular import crt  # Importazione della funzione Teorema del Resto Cinese dalla libreria sympy
import random  # Importazione del modulo random
from sympy import primerange  # Importazione della funzione primerange dalla libreria sympy
from itertools import combinations  # Importazione della funzione combinations dal modulo itertools
from math import prod  # Importazione della funzione prod dal modulo math
import time  # Importazione del modulo time

# Funzione per generare moduli coprimi
def generate_coprime_moduli(info_length, redundant_length, start=2):
    primes = []
    tmp = list(primerange(start, 15))  # Generazione di una lista di numeri primi tra start e 15
    for i in range(info_length + redundant_length):
        primes.append(random.choice(tmp))  # Selezione casuale di numeri primi dalla lista
        tmp.remove(primes[i])  # Rimozione del numero primo selezionato dalla lista
    info_moduli = primes[:info_length]  # Selezione dei primi info_length numeri primi come moduli informativi
    redundant_moduli = primes[info_length:info_length + redundant_length]  # Selezione dei successivi redundant_length numeri primi come moduli ridondanti
    all_moduli = info_moduli + redundant_moduli  # Combinazione dei moduli informativi e ridondanti
    return info_moduli, redundant_moduli, all_moduli

# Funzione per codificare un numero usando i moduli
def encode_number(number, moduli):
    residues = [number % m for m in moduli]  # Calcolo dei residui del numero modulo ciascun modulo
    return residues

# Funzione per decodificare i residui usando il Teorema del Resto Cinese (CRT)
def decode_residues(residues, moduli):
    result = crt(moduli, residues)  # Applicazione del Teorema del Resto Cinese per decodificare i residui
    if result is None:
        raise ValueError("Non esiste una soluzione per i moduli e i residui dati")
    number, _ = result
    return number

# Funzione per ottenere i residui dall'utente tramite input
def get_user_residues(count):
    user_residues = []
    print(f"Inserisci {count} residui:")
    for _ in range(count):
        residue = int(input("Residuo: "))
        user_residues.append(residue)
    return user_residues

# Funzione per calcolare il tempo medio di una funzione
def average_time(func, *args, n_runs=100):
    start_time = time.time()
    for _ in range(n_runs):
        func(*args)
    end_time = time.time()
    avg_time = (end_time - start_time) / n_runs
    return avg_time

# Configurazione iniziale
max_n = 6
n = 4

# Calcolo del tempo medio per la generazione dei moduli
avg_generate_moduli_time = average_time(generate_coprime_moduli, n, max_n - n)
print(f"Tempo medio per la generazione dei moduli: {avg_generate_moduli_time:.6f} secondi")

# Generazione dei moduli informativi e ridondanti
info_moduli, redundant_moduli, all_moduli = generate_coprime_moduli(n, max_n - n)
print("Moduli informativi:", info_moduli)
print("Moduli ridondanti:", redundant_moduli)
print("Tutti i moduli:", all_moduli)

# Calcolo del prodotto di tutti i moduli
max_secret = prod(sorted(all_moduli)[:n])  # Calcolo del prodotto dei primi n moduli
print(f"Il massimo numero che può essere codificato è: {max_secret - 1}")

# Numero da codificare (assicurarsi che sia inferiore al prodotto di tutti i moduli)
original_number = random.randint(0, max_secret)
print(f"Numero originale: {original_number}")

# Calcolo del tempo medio per la codifica del numero
avg_encode_time = average_time(encode_number, original_number, all_moduli)
print(f"Tempo medio per la codifica del numero: {avg_encode_time:.6f} secondi")

# Codifica del numero
encoded_residues = encode_number(original_number, all_moduli)
print(f"Residui codificati: {encoded_residues}")

# Creazione di un dizionario
residues_moduli_dict = {}
for residue, modulus in zip(encoded_residues, all_moduli):  # Creazione di un dizionario con i residui come chiavi e i moduli come valori
    if residue in residues_moduli_dict:  # Se il residuo è già presente nel dizionario, aggiungere il modulo alla lista dei moduli
        residues_moduli_dict[residue].append(modulus)  # Altrimenti, creare una nuova chiave con il residuo e il modulo come valori
    else:
        residues_moduli_dict[residue] = [modulus]  # Stampa del dizionario con i residui e i moduli

print(residues_moduli_dict)

# Funzione per simulare l'ottenimento dei residui dall'utente
def simulated_user_residues(encoded_residues, count):
    return random.sample(encoded_residues, count)

# Ottenimento dei residui dall'utente
user_residues = simulated_user_residues(encoded_residues, n)
print(f"Residui dall'utente simulati: {user_residues}")

# Calcolo del tempo medio per la ricostruzione del segreto
def reconstruction_time(user_residues, residues_moduli_dict):
    moduli = []
    for residue in user_residues:
        moduli.append(random.choice(residues_moduli_dict[residue]))
        residues_moduli_dict[residue].remove(moduli[-1])
    return decode_residues(user_residues, moduli)

avg_reconstruction_time = average_time(reconstruction_time, user_residues, residues_moduli_dict)
print(f"Tempo medio per la ricostruzione del segreto: {avg_reconstruction_time:.6f} secondi")

# Decodifica del numero
moduli = []
for residue in user_residues:
    moduli.append(random.choice(residues_moduli_dict[residue]))
    residues_moduli_dict[residue].remove(moduli[-1])

y = decode_residues(user_residues, moduli)
print(f"Numero decodificato: {y}")
print(f"Numero corretto: {original_number}")
if y == original_number:
    print("Decodifica corretta.")
else:
    print("Decodifica errata.")
