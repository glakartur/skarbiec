#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import csv
from tools.cfg import Cfg
from domain.transaction import Transaction
from domain.transactions import  Transactions

class TransactionImporter:
    def __init__(self):
        self.config = Cfg()

    def process(self):
        print("Importing account transactions...")

        knownTransactionsIds = []
        transactions = Transactions()
        for file in os.listdir(self.config.transactionsFolder):
            print("#", file)
            with open(f"{self.config.transactionsFolder}/{file}", "r", encoding = "cp1250") as csvSrc:
                csvSrc.readline()
                csvSrc.readline()
                reader = csv.reader(csvSrc, delimiter=';', quoting=csv.QUOTE_NONE)

                fileTransactionsIds = []
                fileTransactionsIds.clear()

                for row in reader:
                    # print(row)
                    transaction = Transaction(
                        dataTransakcji = row[0],
                        dataKsiegowania = row[1],
                        nadawca = row[2],
                        odbiorca = row[3],
                        tytulem = row[4],
                        kwota = float(row[5].replace(',','.')),
                        waluta = row[6],
                        kwotaWaluta = float(row[7].replace(',','.')),
                        rachunekNadawcy = row[9],
                        rachunekOdbiorcy = row[10])
                    
                    # print(json.dumps(transaction, use_decimal=True))

                    fileTransactionsIds.append(transaction.id)
                    n = fileTransactionsIds.count(transaction.id)
                    if n > 1:
                        transaction.setIdSuffix(n)

                    if transaction.id not in knownTransactionsIds:
                        transactions.append(transaction)
                        knownTransactionsIds.append(transaction.id)

        transactions.save()
        print("done.")

if __name__ == "__main__":
    pass
