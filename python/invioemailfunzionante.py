import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

username = "gamesslayers87@gmail.com"
password = "bxug dopq sqdp gpvi"
mail_from = "gamesslayers87@gmail.com"
mail_to = "nico.fabiano01@gmail.com"
mail_subject = "Token Whatsapp"
mail_body = "Sei il re di valorant e ti meriti un token per whatsapp!p.s. non è vero, ma ti ho fatto uno scherzo!"

mimemsg = MIMEMultipart()
mimemsg['From']=mail_from
mimemsg['To']=mail_to
mimemsg['Subject']=mail_subject
mimemsg.attach(MIMEText(mail_body, 'plain'))
connection = smtplib.SMTP(host='smtp.gmail.com', port=587)
connection.starttls()
connection.login(username,password)
connection.send_message(mimemsg)
connection.quit()
