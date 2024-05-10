import telebot
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
def get_user_from_db(email):
    # Crea un cursore per eseguire le query
    mycursor = mydb.cursor()

    # Query SQL per selezionare l'id_utente corrispondente all'email
    sql = "SELECT nome FROM utenti WHERE email = %s"

    # Esegui la query con il valore dell'email
    mycursor.execute(sql, (email,))

    # Ottieni il risultato della query
    result = mycursor.fetchone()

    # Chiudi il cursore
    mycursor.close()

    # Restituisci l'id_utente se trovato, altrimenti None
    if result:
        return result
    else:
        return None
    
def get_user_id(email):
    # Crea un cursore per eseguire le query
    mycursor = mydb.cursor()

    # Query SQL per selezionare l'id_utente corrispondente all'email
    sql1= "SELECT id_rrns FROM utenti WHERE email = %s"
    # Esegui la query con il valore dell'email
    mycursor.execute(sql1, (email,))
    # Ottieni il risultato della query
    result1= mycursor.fetchone()
    if result1 is None:
        print("No user found with this email")
        return
    print("User found, id_rrns:", result1[0])  # Debug print
    sql2 = "SELECT rrns_token1 FROM rrns_secret WHERE id_rrns = %s"
    # Esegui la query con il valore di id_rrns
    mycursor.execute(sql2, (result1[0],))  # Pass the actual value, not the tuple
    # Ottieni il risultato della query
    result2 = mycursor.fetchone()
    if result2 is None:
        print("No rrns_secret found for this user")
        return
    print("rrns_secret found, rrns_token1:", result2[0])  # Debug print
    # Chiudi il cursore
    mycursor.close()
    return result2[0]

    

file_csv=r'C:\xampp\htdocs\ProgTesi\php\dati_utenti.csv'
nome=''
cognome=''
email=''

with open(file_csv, 'r') as file:
    csv_reader = csv.reader(file)
    # Leggi tutte le righe del file CSV
    righe_csv = list(csv_reader)
    # Estrai l'ultima riga (la riga appena inserita)
    ultima_riga = righe_csv[-1]

    # Verifica se ci sono abbastanza colonne nella riga
    if len(ultima_riga) >= 3:
        # Estrai i dati dalle colonne
        nome = ultima_riga[0]
        cognome = ultima_riga[1]
        email = ultima_riga[2]
        print(get_user_id(email))

@bot.message_handler(commands=['start'])
def start(message):
    msg = bot.reply_to(message, 'Benvenuto! Per favore, inserisci la tua email.')
    bot.register_next_step_handler(msg, process_email_step)

def process_email_step(message):
    # Qui puoi processare l'email dell'utente
    email = message.text
    bot.reply_to(message, f'Grazie, ho ricevuto la tua email:{email}')

def process_email_step(message):
    # Qui puoi processare l'email dell'utente
    email = message.text
    user = get_user_from_db(email)
    if user is None:
        bot.reply_to(message, 'Non sei registrato. Per favore, registrati.')
    else:
        bot.reply_to(message, f'Benvenuto, {user}!')  # assumendo che il nome sia il secondo campo nel record dell'utente

@bot.message_handler(commands=['stop'])
def stop(message):
    polling = bot.stop_polling()
    bot.reply_to(message, 'Arrivederci! Se hai bisogno di assistenza, contattaci tramite email.')
@bot.message_handler(commands=['token'])
def token(message):
    bot.reply_to(message, f'Ecco il primo token generato: {get_user_id(email)}')
polling = bot.polling()
