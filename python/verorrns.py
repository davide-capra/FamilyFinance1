from sympy.ntheory.modular import crt
import random
from math import gcd
from itertools import combinations
from sympy import primerange
import time
import matplotlib.pyplot as plt


def generate_coprime_moduli(info_length, redundant_length, start=2):
    primes = list(primerange(start, start + 2*(info_length + redundant_length)))  # Genera un elenco di numeri primi
    info_moduli = primes[:info_length]  # Restituisce i primi 'info_length' numeri primi per l'informazione
    redundant_moduli = primes[info_length:info_length + redundant_length]  # Restituisce i successivi 'redundant_length' numeri primi per la ridondanza
    all_moduli = info_moduli + redundant_moduli
    return info_moduli, redundant_moduli, all_moduli

# Funzione per codificare il numero utilizzando i moduli
def encode_number(number, moduli):
    residues = [number % m for m in moduli]
    return residues

# Funzione per decodificare i residui utilizzando il CRT
def decode_residues(residues, moduli):
    result = crt(moduli, residues)
    if result is None:
        raise ValueError("No solution exists for the given moduli and residues")
    number, _ = result
    return number

num_trials = 1000

generations_time = []
encodings_time = []

for _ in range(num_trials):
    start_time=time.time()
    info_moduli, redundant_moduli, all_moduli = generate_coprime_moduli(4,1)
    # Calcola il prodotto dei moduli informativi
    max_number = 1
    for mod in info_moduli:
        max_number *= mod

print("Massimo numero codificabile:", max_number)
print(info_moduli)  # Output: [2, 3, 5]
print(redundant_moduli)  # Output: [7, 11]
print(all_moduli)  # Output: [2, 3, 5, 7, 11]

# Numero da codificare
original_number = random.randint(1, max_number)
    
# Codifica del numero
encoded_residues = encode_number(original_number, all_moduli)
print(f"Numero originale: {original_number}")
print(f"Residui codificati: {encoded_residues}")


redundant_length = len(all_moduli) - len(info_moduli) #

# Introduzione di errori nei residui
erroneous_residues = encoded_residues[:]
error_indices = random.sample(range(len(erroneous_residues)), redundant_length)  # seleziona 'redundant_length' indici a caso
for error_index in error_indices:
    erroneous_residues[error_index] = (erroneous_residues[error_index] + 1) % all_moduli[error_index]

# Stampa i residui con errore

print(f"Residui con errore: {erroneous_residues}")


# Decodifica dei residui errati (senza correzione)
decoded_number_with_error = decode_residues(erroneous_residues, all_moduli)
print(f"Numero decodificato senza correzione: {decoded_number_with_error}")

# Tentiamo di decodificare i residui corretti rimuovendo uno alla volta i residui ridondanti
corrected_number = None
redundant_length = len(all_moduli) - len(info_moduli)  # calcola il numero di moduli ridondanti
for num_errors in range(1, redundant_length + 1):  # prova a correggere fino a 'redundant_length' errori
    if corrected_number is not None:  # se abbiamo già corretto il numero, interrompiamo il ciclo
        break
    for indices in combinations(range(len(all_moduli)), len(all_moduli) - num_errors):  # prova a correggere 'num_errors' residui alla volta
        try_moduli = [all_moduli[i] for i in indices]  # Rimuovi i moduli non selezionati
        try_residues = [erroneous_residues[i] for i in indices]  # Rimuovi i residui non selezionati
        try:  # Prova a decodificare i residui rimanenti
            candidate_number = decode_residues(try_residues, try_moduli)  # Verifica se il numero decodificato corrisponde ai residui originali
            if encode_number(candidate_number, all_moduli) == encoded_residues:  # Se corrisponde, il numero è corretto
                corrected_number = candidate_number  # Salva il numero corretto
                break
        except ValueError: 
            continue

if corrected_number is not None:
    print(f"Segreto corretto: {corrected_number}")
    corrected_residues = encode_number(corrected_number, all_moduli)
    print(f"Residui corretti: {corrected_residues}")
else:
    print("Errore non correggibile.")


# Identificazione e correzione degli errori
# Tentiamo di decodificare i residui corretti rimuovendo uno alla volta i residui ridondanti
#corrected_number = None
#for i in range(len(all_moduli)): # Prova a correggere un residuo alla volta
#    try_moduli = all_moduli[:i] + all_moduli[i+1:] # Rimuovi il modulo i-esimo
#   try_residues = erroneous_residues[:i] + erroneous_residues[i+1:] # Rimuovi il residuo i-esimo
#    try:# Prova a decodificare i residui rimanenti
#        candidate_number = decode_residues(try_residues, try_moduli)# Verifica se il numero decodificato corrisponde ai residui originali
#        if encode_number(candidate_number, all_moduli) == encoded_residues:# Se corrisponde, il numero è corretto
#            corrected_number = candidate_number # Salva il numero corretto
#            break
#    except ValueError: 
#        continue

# Identificazione e correzione degli errori
