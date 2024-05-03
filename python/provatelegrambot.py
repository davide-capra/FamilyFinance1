import telebot
import random
from sympy import primerange

import mysql.connector

# Inizializza il bot
bot = telebot.TeleBot("6402364104:AAH_HeQW1K9ILzlYeKg4nPR83xg__TguIsU")  # Sostituisci "TOKEN" con il token del tuo bot

# Funzione per gestire il comando /start
@bot.message_handler(commands=['start'])
def start(message):
    bot.reply_to(message, 'Benvenuto! Per registrarti, inviami i seguenti dettagli:\n\nNome\nCognome\nEmail\nNumero di telefono\nPassword')

# Funzione per gestire i messaggi di testo
@bot.message_handler(func=lambda message: True)
def registrazione(message):
    user_id = message.from_user.id
    text = message.text.split('\n')
    
    # Assicurati che tutti i campi siano stati inseriti
    if len(text) != 5:
        bot.reply_to(message, 'Inserisci tutti i dettagli richiesti correttamente.')
        return

    name, surname, email, phone_number, password = text

    # Connetti al database MySQL
    mydb = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="tesi"
    )

    mycursor = mydb.cursor()

    # Inserisci l'utente nel database
    sql = "INSERT INTO utenti (id_utente,nome,cognome, email,cellulare,password) VALUES (%s, %s, %s, %s, %s, %s)"
    val = (user_id, name, surname, email, phone_number, password)
    mycursor.execute(sql, val)
    mydb.commit()

    bot.reply_to(message, f'Utente {name} {surname} registrato con successo!')

# Avvia il bot
bot.polling()

