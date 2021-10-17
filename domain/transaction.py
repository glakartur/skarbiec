#!/usr/bin/env python
# -*- coding: utf-8 -*-

import hashlib
import base64

class Transaction:
    def __init__(self, dataTransakcji, dataKsiegowania, nadawca, odbiorca, tytulem, kwota, waluta, kwotaWaluta, rachunekNadawcy, rachunekOdbiorcy):
        self.date = dataTransakcji
        self.booked = dataKsiegowania
        self.sender = nadawca
        self.receiver = odbiorca
        self.description = tytulem
        self.saleMoney = kwota
        self.saleCurrency = waluta
        self.money = kwotaWaluta
        self.senderAccount = rachunekNadawcy
        self.receiverAccount = rachunekOdbiorcy

        hasher = hashlib.sha256(usedforsecurity=False)
        hasher.update(bytes(self.date, 'utf-8'))
        hasher.update(bytes(self.booked, 'utf-8'))
        hasher.update(bytes(self.sender, 'utf-8'))
        hasher.update(bytes(self.receiver, 'utf-8'))
        hasher.update(bytes(self.description, 'utf-8'))
        hasher.update(bytes(str(self.saleMoney), 'utf-8'))
        hasher.update(bytes(self.saleCurrency, 'utf-8'))
        hasher.update(bytes(str(self.money), 'utf-8'))
        hasher.update(bytes(self.senderAccount, 'utf-8'))
        hasher.update(bytes(self.receiverAccount, 'utf-8'))
        self.id = base64.b64encode(hasher.digest()).decode()

    def setIdSuffix(self, suffix):
        self.id += f":{suffix}"

if __name__ == "__main__":
    pass