import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import sys

# Get command line arguments
email = sys.argv[1] 
token2 = sys.argv[2]
token3 = sys.argv[3]
token4 = sys.argv[4]

username = "gamesslayers87@gmail.com"
password = "bxug dopq sqdp gpvi"
mail_from = "gamesslayers87@gmail.com"
mail_to = "davidecapra12@gmail.com"
mail_subject = "Token Family Finance"
mail_body = "Ciao, ecco i tuoi token: " + token2 + ", " + token3 + ", " + token4
mimemsg = MIMEMultipart() # creazione di un oggetto messaggio
mimemsg['From']=mail_from # mittente
mimemsg['To']=mail_to # destinatario
mimemsg['Subject']=mail_subject # oggetto
mimemsg.attach(MIMEText(mail_body, 'plain')) # allegato
connection = smtplib.SMTP(host='smtp.gmail.com', port=587) # connessione al server
connection.starttls() # criptazione della connessione 
connection.login(username,password) # login
connection.send_message(mimemsg) # invio del messaggio
connection.quit()


