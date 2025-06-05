# MFA RRNS - Sistema di Autenticazione Multi-Fattore Basato su Redundant Residue Number System

## Descrizione

Progetto di sistema di autenticazione multi-fattore (MFA) che utilizza il Redundant Residue Number System (RRNS) e il Teorema Cinese del Resto (CTR) per garantire un elevato livello di sicurezza nella verifica degli utenti. Il sistema integra secret sharing per dividere e ricostruire segreti in modo sicuro, e invia i token di autenticazione tramite email, SMS e bot Telegram.

---

## Architettura del Sistema

- **Client Web**: Interfaccia utente per inserimento credenziali e token 2FA, comunicazione sicura via HTTPS.
- **Server di Autenticazione**: Gestisce login, verifica credenziali, e coordina il modulo di secret sharing.
- **Modulo di Secret Sharing**: Divide e ricostruisce segreti tramite RRNS e CTR.
- **Database**: Conserva share dei segreti, credenziali utenti, token Telegram.
- **Canali di Comunicazione**: Email, SMS, Telegram per l'invio sicuro dei token 2FA.

---

## Funzionamento

1. **Prima Autenticazione**: Login con username e password.
2. **Generazione Token**: Creazione dei token RRNS inviati tramite canali sicuri.
3. **Seconda Autenticazione**: Inserimento e verifica token per ricostruire il segreto.
4. **Accesso Consentito**: Se verifiche corrette, l'utente accede al sistema.

---

## Tecnologie Utilizzate

- **Python**: Implementazione dell’algoritmo RRNS e Secret Sharing.
- **Sympy**: Generazione numeri primi, calcoli matematici, implementazione CTR.
- **mysql.connector**: Connessione al database MySQL.
- **smtplib & email.mime.multipart**: Invio email tramite SMTP.
- **telebot**: Gestione invio messaggi tramite bot Telegram.
- **Frontend Web**: HTML, CSS, JavaScript, jQuery, PHP per l’interfaccia utente.

---

## Diagramma E/R

Schema di autenticazione utente collegato al sistema RRNS, con tabelle principali:

- **Utente**: id_utente, email, chat_id Telegram, id_rrns (chiave esterna)
- **RRNS_Secret**: id_rrns, segreto hashato, token_telegram

---

## Come avviare il progetto

1. Configurare il database MySQL con le tabelle `Utente` e `RRNS_Secret`.
2. Impostare le credenziali per SMTP e bot Telegram nei file di configurazione.
3. Avviare il server Python che gestisce l’algoritmo RRNS e il modulo di autenticazione.
4. Eseguire il sito web per l’interfaccia utente.

---

## Contatti

Per domande o supporto: [tuo indirizzo email]
