import telebot
import random
from sympy import primerange
from random import sample
from sympy import prod
from random import randrange
from sympy.ntheory.modular import crt
import mysql.connector
import csv

# Inizializza il bot
bot = telebot.TeleBot("6402364104:AAH_HeQW1K9ILzlYeKg4nPR83xg__TguIsU")  # Sostituisci "TOKEN" con il token del tuo bot
# Connetti al database MySQL
mydb = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="tesi"
    )
@bot.message_handler(commands=['start'])
def start_message(message):
    chat_id = message.chat.id
    cursor = mydb.cursor()  # Define the "cursor" variable
    query = "SELECT * FROM utenti WHERE chat_id = %s"
    cursor.execute(query, (chat_id,))
    results = cursor.fetchall()
    if results:
        bot.reply_to(message, "Sei già registrato. Puoi utilizzare il comando /token per ottenere il tuo token.")
    else:
        bot.reply_to(message, "Benvenuto! Per poter utilizzare il bot, inserisci la tua email.")
        bot.reply_to(message, "Inserisci la tua email:")
        @bot.message_handler(func=lambda message: True)
        def insert_email(message):
            email = message.text
            query = "UPDATE utenti SET chat_id = %s WHERE email = %s"
            cursor.execute(query, (chat_id, email))
            mydb.commit()
            bot.reply_to(message, "Inserimento completato. Ora puoi utilizzare il bot.")

@bot.message_handler(commands=['token'])
def token(message):
    chat_id = message.chat.id
    print(chat_id)
    cursor = mydb.cursor()  # Define the "cursor" variable
    query = "SELECT id_rrns FROM utenti WHERE chat_id = %s"
    cursor.execute(query, (chat_id,))
    results = cursor.fetchall()
    if results:
        id_rrns = results[0][0]
        query = "SELECT rrns_token1 FROM rrns_secret WHERE id_rrns = %s"
        cursor.execute(query, (id_rrns,))
        results = cursor.fetchall()
        if results:
            tokens = results[0]
            bot.reply_to(message, f'Il tuo token è: {tokens}')
        else:
            bot.reply_to(message, "Si è verificato un problema.")
    else:
        bot.reply_to(message, "Non hai eseguito l'accesso al sito per questo chat_id.")

#avvio del bot
bot.polling()