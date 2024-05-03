import random
from sympy import primerange
from sympy.ntheory.modular import crt

# Funzione per generare un numero casuale compreso tra minimo e massimo
def generate_random_number(minimo, massimo):
    return random.randint(minimo, massimo)

# Funzione per trovare i numeri primi relativi a un numero dato
def find_primes_relative_to_number(number):
    return list(primerange(2, number))

# Funzione per recuperare il segreto utilizzando il teorema dei resti cinesi
def recover_secret(moduli, resti):
    return crt(moduli, resti)[0]

# Genera un numero casuale tra 10000 e 999999
numero_casuale = generate_random_number(10000, 999999)

# Trova i numeri primi relativi al numero casuale
numeri_primi = find_primes_relative_to_number(numero_casuale)
print("Numeri primi relativi al numero casuale:", numeri_primi)
print("Numero casuale generato:", numero_casuale)

# Chiedi all'utente di inserire i 4 numeri primi
print("Inserisci i 4 numeri primi:")
numeri_primi_inseriti = [int(input("Numero primo {}: ".format(i+1))) for i in range(4)]

# Calcola i resti
resti = [numero_casuale % primo for primo in numeri_primi_inseriti]

# Recupera il segreto utilizzando il teorema dei resti cinesi
segreto_recuperato = recover_secret(numeri_primi_inseriti, resti)
print("Segreto recuperato:", segreto_recuperato)

# Verifica se il segreto recuperato è corretto
if segreto_recuperato == numero_casuale:
    print("Il segreto è stato recuperato correttamente.")
else:
    print("Il segreto non è stato recuperato correttamente.")
