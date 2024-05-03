import random
from sympy import primerange
from random import sample
from sympy import prod
from random import randrange
from sympy.ntheory.modular import crt

# Genera una lista casuale di numeri primi come moduli
def generate_random_moduli(num_moduli, min_prime, max_prime):
    primes = list(primerange(min_prime, max_prime))
    return sample(primes, num_moduli)

# Numero di moduli desiderati
num_moduli = 4

# Range per la generazione casuale dei numeri primi
min_prime = 2
max_prime = 13

# Genera la lista casuale di moduli
moduli=generate_random_moduli(num_moduli, min_prime, max_prime)
print("Moduli generati:", moduli)

# Calcola il prodotto dei moduli
M =prod(moduli)
print("Prodotto dei moduli:", M)
# Genera un numero casuale minore di M
x =randrange(M)

# Calcola i resti della divisione di x per ciascun modulo
remainders = [x % m for m in moduli]

# Utilizza il teorema cinese del resto per trovare y
y = crt(moduli, remainders, symmetric=False)[0]

# Stampa i risultati
print("Numero casuale x:", x)
print("Resti:", remainders)
print("Valore calcolato y:", y)
